import sys
import decimal

# --- MONKEY PATCH DECIMAL UNTUK PYRFC ---
_orig_decimal = decimal.Decimal

class SafeDecimal(_orig_decimal):
    def __new__(cls, value="0", context=None):
        try:
            return _orig_decimal.__new__(cls, value, context)
        except decimal.InvalidOperation:
            return _orig_decimal.__new__(cls, "0", context)

decimal.Decimal = SafeDecimal
# ----------------------------------------

from flask import Flask, request, jsonify
from flask_cors import CORS
from pyrfc import (
    Connection,
    ABAPApplicationError,
    ABAPRuntimeError,
    LogonError,
    CommunicationError,
    RFCError,
    RFCLibError,
)
from decimal import Decimal, InvalidOperation
from datetime import date, datetime, time
import os
import logging
from logging.handlers import RotatingFileHandler

app = Flask(__name__)
CORS(app)

# ---------------------------
# Logging
# ---------------------------
logger = logging.getLogger("production_orders_api")
logger.setLevel(logging.INFO)

if not logger.handlers:
    formatter = logging.Formatter(
        "[%(asctime)s] %(levelname)s in %(module)s: %(message)s"
    )

    file_handler = RotatingFileHandler(
        "production_orders_api.log",
        maxBytes=10 * 1024 * 1024,
        backupCount=5
    )
    file_handler.setFormatter(formatter)
    file_handler.setLevel(logging.INFO)
    logger.addHandler(file_handler)

    console_handler = logging.StreamHandler()
    console_handler.setFormatter(formatter)
    console_handler.setLevel(logging.INFO)
    logger.addHandler(console_handler)


# ---------------------------
# Helpers
# ---------------------------
def pad12(value: str) -> str:
    value = str(value or "").strip()
    return value.zfill(12) if value else ""


def require_internal_token():
    expected_token = (
        os.environ.get("SAP_FLASK_TOKEN")
    )

    if not expected_token:
        raise RuntimeError(
            "SAP_FLASK_TOKEN belum diset di environment."
        )

    incoming_token = request.headers.get("X-Internal-Token", "").strip()
    if incoming_token != expected_token:
        raise PermissionError("Invalid X-Internal-Token")


def connect_sap():
    user = os.environ.get("SAP_USER")
    password = os.environ.get("SAP_PASSWD")

    if not user or not password:
        raise RuntimeError("SAP_USER atau SAP_PASSWD belum diset di environment.")

    return Connection(
        user=user,
        passwd=password,
        ashost=os.environ.get("SAP_ASHOST", "192.168.254.154"),
        sysnr=os.environ.get("SAP_SYSNR", "01"),
        client=os.environ.get("SAP_CLIENT", "300"),
        lang=os.environ.get("SAP_LANG", "EN"),
    )


def normalize_value(value):
    if isinstance(value, Decimal):
        return float(value)
    if isinstance(value, (datetime, date)):
        return value.isoformat()
    if isinstance(value, time):
        return value.isoformat()
    return value


def normalize_rows(rows):
    normalized = []
    for row in rows or []:
        if isinstance(row, dict):
            normalized.append({k: normalize_value(v) for k, v in row.items()})
        else:
            normalized.append(row)
    return normalized


def extract_return_messages(return_table):
    messages = []
    for row in return_table or []:
        if isinstance(row, dict):
            messages.append({
                "TYPE": row.get("TYPE"),
                "ID": row.get("ID"),
                "NUMBER": row.get("NUMBER"),
                "MESSAGE": row.get("MESSAGE"),
            })
    return messages


# ---------------------------
# Routes
# ---------------------------
@app.route("/health", methods=["GET"])
def health():
    return jsonify({
        "ok": True,
        "service": "production-orders-api"
    }), 200


@app.route("/rfc/production-orders", methods=["GET"])
def production_orders():
    conn = None

    try:
        require_internal_token()

        p_werks = (request.args.get("p_werks") or "").strip()
        p_aufnr = (request.args.get("p_aufnr") or "").strip()

        if not p_werks:
            return jsonify({
                "ok": False,
                "message": "Parameter p_werks wajib diisi."
            }), 400

        if p_aufnr:
            p_aufnr = pad12(p_aufnr)

        sap_timeout = int(os.environ.get("SAP_RFC_TIMEOUT", "3600"))

        logger.info(
            "Calling RFC Z_FM_YPPR074Z with P_WERKS=%s, P_AUFNR=%s",
            p_werks,
            p_aufnr or ""
        )

        conn = connect_sap()
        result = conn.call(
            "Z_FM_YPPR074Z",
            options={"timeout": sap_timeout},
            P_WERKS=p_werks,
            P_AUFNR=p_aufnr if p_aufnr else ""
        )

        return_table = normalize_rows(result.get("RETURN", []) or [])
        t_data1 = normalize_rows(result.get("T_DATA1", []) or [])

        if p_aufnr:
            t_data1 = [
                row for row in t_data1
                if str(row.get("AUFNR", "")).strip() == p_aufnr
            ]

        return jsonify({
            "ok": True,
            "rfc_name": "Z_FM_YPPR074Z",
            "parameters": {
                "P_WERKS": p_werks,
                "P_AUFNR": p_aufnr or None,
            },
            "count": len(t_data1),
            "return": extract_return_messages(return_table),
            "t_data1": t_data1,
        }), 200

    except PermissionError as e:
        return jsonify({
            "ok": False,
            "message": str(e)
        }), 401

    except ValueError as e:
        return jsonify({
            "ok": False,
            "message": str(e)
        }), 400

    except RFCError as e:
        key = getattr(e, "key", "")
        message = getattr(e, "message", str(e))

        if key == "RFC_CANCELED":
            return jsonify({
                "ok": False,
                "message": "SAP call timed out",
                "detail": message,
            }), 504

        logger.exception("SAP RFC error")
        return jsonify({
            "ok": False,
            "message": "SAP RFC error",
            "detail": message,
        }), 502

    except (
        ABAPApplicationError,
        ABAPRuntimeError,
        CommunicationError,
        LogonError,
        RFCLibError,
    ) as e:
        logger.exception("SAP connection/runtime error")
        return jsonify({
            "ok": False,
            "message": "SAP connection/runtime error",
            "detail": str(e),
        }), 502

    except InvalidOperation as e:
        logger.exception("SAP returned invalid decimal format")
        return jsonify({
            "ok": False,
            "message": "Data di dalam SAP mengandung field decimal/angka yang tidak valid.",
            "detail": str(e),
        }), 502

    except Exception as e:
        logger.exception("Unhandled error")
        return jsonify({
            "ok": False,
            "message": str(e),
        }), 500

    finally:
        if conn:
            try:
                conn.close()
            except Exception:
                pass

@app.route("/rfc/sales-orders", methods=["GET"])
def sales_orders():
    conn = None

    try:
        require_internal_token()

        iv_auart = (request.args.get("iv_auart") or "").strip()
        iv_balance = (request.args.get("iv_balance") or "").strip()
        iv_werks = (request.args.get("iv_werks") or "").strip()

        # Optional validation
        if iv_balance and iv_balance not in ("X", "x", "0", "1"):
            return jsonify({
                "ok": False,
                "message": "Parameter iv_balance tidak valid. Gunakan X / 1 untuk balance, atau kosongkan."
            }), 400

        if iv_balance == "1":
            iv_balance = "X"
        elif iv_balance == "0":
            iv_balance = ""

        sap_timeout = int(os.environ.get("SAP_RFC_TIMEOUT", "3600"))

        logger.info(
            "Calling RFC Z_FM_YPPR079_SO with IV_AUART=%s, IV_BALANCE=%s, IV_WERKS=%s",
            iv_auart or "",
            iv_balance or "",
            iv_werks or ""
        )

        conn = connect_sap()
        result = conn.call(
            "Z_FM_YPPR079_SO",
            options={"timeout": sap_timeout},
            IV_AUART=iv_auart,
            IV_BALANCE=iv_balance,
            IV_WERKS=iv_werks
        )

        return_table = normalize_rows(result.get("RETURN", []) or [])
        t_data1 = normalize_rows(result.get("T_DATA1", []) or [])
        t_data2 = normalize_rows(result.get("T_DATA2", []) or [])
        t_data3 = normalize_rows(result.get("T_DATA3", []) or [])
        t_data4 = normalize_rows(result.get("T_DATA4", []) or [])

        return jsonify({
            "ok": True,
            "rfc_name": "Z_FM_YPPR079_SO",
            "parameters": {
                "IV_AUART": iv_auart or None,
                "IV_BALANCE": iv_balance or None,
                "IV_WERKS": iv_werks or None,
            },
            "count": {
                "T_DATA1": len(t_data1),
                "T_DATA2": len(t_data2),
                "T_DATA3": len(t_data3),
                "T_DATA4": len(t_data4),
            },
            "return": extract_return_messages(return_table),
            "t_data1": t_data1,
            "t_data2": t_data2,
            "t_data3": t_data3,
            "t_data4": t_data4,
        }), 200

    except PermissionError as e:
        return jsonify({
            "ok": False,
            "message": str(e)
        }), 401

    except ValueError as e:
        return jsonify({
            "ok": False,
            "message": str(e)
        }), 400

    except RFCError as e:
        key = getattr(e, "key", "")
        message = getattr(e, "message", str(e))

        if key == "RFC_CANCELED":
            return jsonify({
                "ok": False,
                "message": "SAP call timed out",
                "detail": message,
            }), 504

        logger.exception("SAP RFC error")
        return jsonify({
            "ok": False,
            "message": "SAP RFC error",
            "detail": message,
        }), 502

    except (
        ABAPApplicationError,
        ABAPRuntimeError,
        CommunicationError,
        LogonError,
        RFCLibError,
    ) as e:
        logger.exception("SAP connection/runtime error")
        return jsonify({
            "ok": False,
            "message": "SAP connection/runtime error",
            "detail": str(e),
        }), 502

    except InvalidOperation as e:
        logger.exception("SAP returned invalid decimal format")
        return jsonify({
            "ok": False,
            "message": "Data di dalam SAP mengandung field decimal/angka yang tidak valid.",
            "detail": str(e),
        }), 502

    except Exception as e:
        logger.exception("Unhandled error")
        return jsonify({
            "ok": False,
            "message": str(e),
        }), 500

    finally:
        if conn:
            try:
                conn.close()
            except Exception:
                pass


if __name__ == "__main__":
    app.run(
        host=os.environ.get("FLASK_HOST", "0.0.0.0"),
        port=int(os.environ.get("FLASK_PORT", "5001")),
        debug=os.environ.get("FLASK_DEBUG", "false").lower() == "true",
        use_reloader=False,
    )