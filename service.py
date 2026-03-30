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
from decimal import Decimal
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