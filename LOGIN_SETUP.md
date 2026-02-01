# Sistema de Login - Instrucciones de Configuración

## 📋 Archivos Creados

1. **app/Controllers/Auth.php** - Controlador de autenticación
2. **app/Models/User.php** - Modelo de usuario
3. **app/Views/auth/login.php** - Vista del formulario de login
4. **app/Database/Migrations/2026-02-01-000001_CreateUsersTable.php** - Migración de BD
5. **app/Filters/AuthFilter.php** - Filtro de autenticación
6. **app/Config/Routes.php** - Rutas actualizadas

---

## 🚀 Pasos de Instalación

### 1. Ejecutar la Migración
```bash
php spark migrate
```

### 2. Insertar Usuarios de Prueba (Opcional)
Accede a tu base de datos y ejecuta:
```sql
INSERT INTO users (name, email, pin, created_at, updated_at) VALUES 
('Admin', 'admin@ejemplo.com', '1234', NOW(), NOW()),
('Usuario Test', 'test@ejemplo.com', '5678', NOW(), NOW());
```

### 3. Acceder al Login
Navega a: `http://tu-proyecto/login`

---

## 📝 Estructura de Comentarios

Todos los archivos siguen el formato de numeración:
- **X.0** = Bloque principal
- **X.1, X.2, X.3...** = Subprocesos dentro del bloque

Ejemplo:
```
// 1.0 Descripción del bloque principal
    // 1.1 Primer proceso
    // 1.2 Segundo proceso
```

---

## 🎨 Personalización de Estilos

En [app/Views/auth/login.php](app/Views/auth/login.php) puedes cambiar:

### Colores principales:
- **Verde (#4CAF50)**: Cambiar en sección `2.9` del CSS
- **Fondo gris (#f5f5f5)**: Cambiar en sección `2.2`
- **Blanco (#ffffff)**: Cambiar en sección `2.3`

### Ejemplo - Cambiar color principal a azul:
```css
/* 2.9 Estilos del botón */
button {
    background-color: #2196F3;  /* Cambiar aquí */
}

button:hover {
    background-color: #0b7dda;
}
```

---

## 🔐 Datos del Login

- **Email**: cualquier correo válido en la tabla users
- **PIN**: 4 dígitos numéricos (0000-9999)
- **Sin Password**: Sistema solo usa PIN de 4 dígitos

---

## 📊 Variables de Sesión

Después de login exitoso, disponibles:
```php
session('user_id')      // ID del usuario
session('email')        // Email del usuario
session('name')         // Nombre del usuario
session('isLoggedIn')   // Boolean true
```

---

## 🛡️ Proteger Rutas

Para proteger rutas requiriendo autenticación, en [app/Config/Routes.php](app/Config/Routes.php):
```php
$routes->get('/dashboard', 'Home::dashboard', ['filter' => 'auth']);
```

---

## 🧪 Endpoints Disponibles

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/login` | Mostrar formulario de login |
| POST | `/auth/authenticate` | Procesar autenticación |
| GET | `/logout` | Cerrar sesión |

---

## ⚠️ Notas Importantes

1. El PIN se almacena en texto plano (cambiar en producción)
2. CSRF está habilitado automáticamente
3. Los colores son básicos: personaliza según tu diseño
4. No olvides ejecutar la migración primero

---

## 📌 Próximos Pasos

- [ ] Ejecutar migración (`php spark migrate`)
- [ ] Insertar usuarios de prueba en BD
- [ ] Acceder a /login y probar
- [ ] Personalizar colores y estilos
- [ ] Implementar encriptación de PIN
- [ ] Agregar 2FA (autenticación de dos factores)
