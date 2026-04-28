<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a Reunión - GM Suministros</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #1a2035; /* Kaiadmin dark theme color */
            padding: 30px;
            text-align: center;
            border-bottom: 4px solid #48abf7; /* Primary blue from theme */
        }
        .header img {
            max-height: 50px;
        }
        .header h1 {
            color: #ffffff;
            margin: 20px 0 0 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .details-box {
            background-color: #f8f9fa;
            border-left: 4px solid #48abf7;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 4px 4px 0;
        }
        .details-box p {
            margin: 5px 0;
            font-size: 15px;
        }
        .details-box strong {
            color: #1a2035;
        }
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        .btn-teams {
            display: inline-block;
            background-color: #4f52b2; /* Teams purple/blue */
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(79, 82, 178, 0.3);
            transition: background-color 0.3s;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #888888;
            border-top: 1px solid #eeeeee;
        }
        .footer p {
            margin: 5px 0;
        }
        .teams-icon {
            vertical-align: middle;
            margin-right: 8px;
            width: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <!-- Usamos base_url() para cargar el logo de la empresa -->
            <img src="<?= base_url('public/assets/img/kaiadmin/banner_gms.jpg') ?>" alt="GM Suministros" style="border-radius: 4px;">
            <h1>Invitación a Reunión Virtual</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hola <strong><?= esc($client_name ?? 'Cliente') ?></strong>,</p>
            <p>Esperamos que te encuentres muy bien.</p>
            <p>El equipo de <strong>GM Suministros</strong> te invita cordialmente a una reunión virtual por Microsoft Teams para revisar detalles importantes y continuar avanzando juntos.</p>

            <!-- Meeting Details -->
            <div class="details-box">
                <p><strong>📅 Fecha:</strong> <?= esc($meeting_date ?? 'Por definir') ?></p>
                <p><strong>⏰ Hora:</strong> <?= esc($meeting_time ?? 'Por definir') ?></p>
                <p><strong>📝 Asunto:</strong> <?= esc($meeting_subject ?? 'Reunión de Seguimiento') ?></p>
            </div>

            <p style="text-align: center; color: #666;">Para unirte a la reunión, haz clic en el siguiente botón en la fecha y hora indicadas:</p>

            <!-- Call to Action -->
            <div class="button-container">
                <a href="<?= $teams_link ?? '#' ?>" class="btn-teams">
                    <!-- Icono base64 de Teams para evitar bloqueos de imagen externa -->
                    <img class="teams-icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACxUlEQVR4nO2Xv2sUQRTHP7s3l8R/ICiihSCKglgoQcXCzj/BQizsRAstNCkU0U4sBcGwK2wEwT+gEBS0sBFEi6QIggHBRhJzc+flnB1v3I27N3P7YyX7gWF2Z3bm+773dt7M+6CgoKCgoKCgoKCgoKCgoPB/IR5d0x7cBcYB/10eQ8B2Z+Y56e4nLd1FvFkAXN182g2mFvN7Q2Mv5iJg12Q2vJgHAnNnsAAYkdjIu1tYvG8B/e1c070g711BPMH+b4XzYnO3r81/y833y+4vX+5NdfkXzP42XfM+gI3B0n1X0D8u/vY84n0K6MtkY2Ym1/Xl771O9xSwsbO/nO4mYEdnZt7c82g3E29U85oVzQfE0y1tB/yT4m+v1rQeEM82M0+Lvz1f010ETIunZ2ZuiKfz2r4A7BDPRzMzrP2PimfcWc18G+0HhL/000+X0/Pib/eX3zN5wEw3X6N6G/B7NPMp4J14Oh7mZ9r8A2KrmA+J5yPabmD4NfM14C3idQO/gW3iOa3tz4jX62zXqN6K6lqVvE88XQ3zQ22nEa/XkEa1b1SviOcpbX9AvF79BwNnI/94H8BTYAowtX+u/1s6jXjd0iQwmSj+AkwDE0A/sAn4BtwEpoA+YKjB9b0G3gEzwA9gDfAQWASsANa4i71b0zZ2d5X8K+AZcAhYDqztLgL1vGq9oX/H+s8B64GzwEpgDdgI7AL2AieAFeIfXhH1/2o8Xk4Bv8s/WzpwT0QxP/L18rM8H122QY23Wk9E007fB3i7X8t0W69E6/c/0vYh8/e8mXm64m9eC2i/9DciVf/W1LwL1mP9W1H/JjNfBPqM9+l49B2t02B/r2jeB3rM/o405fX8E+B1Lh808kUj/w34p5fBflvQ10Z8b/jP0y5gY7B0T0P/mPjb6227i2R/e6nNf4b235D9v9E6Z6JvL13O/m/y6/W+y5s7S11u8o8EewGwdI/i/1A4k/2d0uU+f3s2r31Q0wAAAABJRU5ErkJggg==" alt="Teams Icon">
                    Unirse a la reunión de Teams
                </a>
            </div>

            <p>Si tienes algún problema para conectarte, por favor responde a este correo y te ayudaremos a la brevedad.</p>
            
            <p>¡Te esperamos!</p>
            <p>Atentamente,<br><strong>El equipo de GM Suministros</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; <?= date('Y') ?> GM Suministros SAS. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no respondas directamente a menos que necesites soporte técnico para la reunión.</p>
        </div>
    </div>
</body>
</html>
