<!DOCTYPE html>
<html lang="es">
<head>
    <!-- 1.0 Configurar metadatos del documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= base_url('public/assets/img/kaiadmin/favicon.ico') ?>">
    <title>GM Suministros - SAS</title>

    <!-- 2.0 Estilos CSS básicos -->
    <style>
        /* 2.1 Resetear estilos por defecto */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* 2.2 Estilos del cuerpo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* 2.3 Contenedor principal */
        .login-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        /* 2.4 Título del formulario */
        .login-title {
            text-align: center;
            color: #333333;
            margin-bottom: 30px;
            font-size: 24px;
        }

        /* 2.5 Contenedor del formulario */
        .login-form {
            display: flex;
            flex-direction: column;
        }

        /* 2.6 Grupos de formulario */
        .form-group {
            margin-bottom: 20px;
        }

        /* 2.7 Estilos de etiquetas */
        label {
            display: block;
            margin-bottom: 8px;
            color: #555555;
            font-weight: bold;
            font-size: 14px;
        }

        /* 2.8 Estilos de campos de entrada */
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        /* 2.9 Estilos del botón */
        button {
            background-color: #5ebc42;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        button:active {
            background-color: #3d8b40;
        }

        /* 2.10 Mensajes de error */
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        /* 2.11 Mensajes de éxito */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        /* 2.12 Mensaje de validación */
        .form-error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }

        /* 2.13 Nota informativa */
        .info-text {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- 3.0 Contenedor principal del login -->
    <div class="login-container">
        
        <!-- 3.1 Título del formulario -->
        <h1 class="login-title">Sistema de Administración</h1>

        <center>
            <img style="width: 50%;" src="<?= base_url('public/assets/img/logo_gms.png') ?>" alt="Banner GMS">
        </center>

        <!-- 3.2 Mostrar mensajes de error -->
        <?php if (session()->has('error')): ?>
            <div class="alert-error">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <!-- 3.3 Mostrar mensajes de éxito -->
        <?php if (session()->has('success')): ?>
            <div class="alert-success">
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <!-- 3.4 Mostrar errores de validación -->
        <?php if (session()->has('errors')): ?>
            <div class="alert-error">
                <?php foreach (session('errors') as $field => $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 4.0 Formulario de autenticación -->
        <form action="<?= base_url('auth/authenticate') ?>" method="POST" class="login-form">
            
            <!-- 4.1 Token CSRF de seguridad -->
            <?= csrf_field() ?>

            <!-- 4.2 Campo de correo electrónico -->
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@correo.com"
                    value="<?= old('email') ?>"
                    required
                >
                <?php if (isset($errors['email'])): ?>
                    <div class="form-error"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>

            <!-- 4.3 Campo PIN de 4 dígitos -->
            <div class="form-group">
                <label for="pin">PIN de Seguridad</label>
                <input 
                    type="password" 
                    id="pin" 
                    name="pin" 
                    placeholder="0000"
                    maxlength="4"
                    inputmode="numeric"
                    pattern="[0-9]{4}"
                    value="<?= old('pin') ?>"
                    required
                >
                <div class="info-text">Ingrese 4 dígitos numéricos</div>
                <?php if (isset($errors['pin'])): ?>
                    <div class="form-error"><?= $errors['pin'] ?></div>
                <?php endif; ?>
            </div>

            <!-- 4.4 Botón de envío -->
            <button type="submit">Iniciar Sesión</button>
        </form>

        <!-- 5.0 Pie de página -->
        <div class="info-text">
            <p>© 2026 Sistema de Administración</p>
        </div>
    </div>

    <!-- 6.0 Script para validación en cliente (opcional) -->
    <script>
        // 6.1 Validar PIN solo números
        document.getElementById('pin').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
