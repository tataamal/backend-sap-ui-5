<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css" />
    <style>
        body { font-family: sans-serif; margin: 0; }
        .swagger-ui .topbar { background-color: #2c3e50; }
        /* Perlebar ruang untuk kolom 'Name' pada tabel Parameter */
        .swagger-ui .parameters-col_name {
            width: 30% !important;
            min-width: 250px !important;
        }
        /* Hilangkan tulisan /openapi.json di bawah judul (secara agresif) */
        .swagger-ui .info a {
            display: none !important;
        }
        /* Kurangi jarak antara Judul, Deskripsi, dan kotak Servers */
        .swagger-ui .info {
            margin: 50px 0 10px 0 !important;
        }
        .swagger-ui .info .title {
            margin-bottom: 0 !important;
        }
        .swagger-ui .info p {
            margin: 5px 0 10px 0 !important;
        }
        .swagger-ui .scheme-container {
            margin: 0 !important;
            padding: 0 0 10px 0 !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script>
        window.onload = () => {
            window.ui = SwaggerUIBundle({
                url: '/openapi.json',
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIBundle.SwaggerUIStandalonePreset
                ],
            });
        };
    </script>
</body>
</html>
