# Registro de Cambios - ReservaYa

## [2026-06-03] - Mejoras de Seguridad e Infraestructura
- **HTTPS Forzado**: Configuración de Apache Proxy para redirigir tráfico HTTP (80) a HTTPS (443).
- **SSL Termination**: Configuración de certificados SSL en el proxy para el dominio 'reservayasamuel.es'.
- **Seguridad en Login**: Implementación de `password_verify()` en `index.php` para validar contraseñas cifradas con BCRYPT.
- **Gestión de Sesiones**: Uso de `$_SESSION` para mantener la identidad del usuario y proteger el acceso al panel.
- **Red de Docker**: Aislamiento de los servicios `app` y `db` en una red privada interna.

## [2026-06-03] - Unificación y Control de Acceso
- **Unificación de Tablas**: Se ha unificado la lógica para que tanto el Panel de Usuario como el Panel de Admin utilicen la tabla `citas`.
- **Control de Acceso Admin**: Se ha protegido `admin.php` con un sistema de sesiones que verifica el rol del usuario (`rol === 'admin'`).
- **Redirección por Rol**: El `index.php` ahora redirige automáticamente a `admin.php` o `panel.php` según el tipo de usuario tras el login.
- **Resolución de DNS Docker**: Se ha actualizado `db.php` para utilizar el nombre del contenedor `mysql_db` y mejorar la fiabilidad de la conexión.
- **Seguridad de Sesión**: Se han añadido llamadas a `session_start()` y validaciones de identidad en todos los puntos críticos.
- **Actualización de Base de Datos**: Ejecución de script SQL para añadir la columna `estado` a la tabla `citas`, permitiendo el seguimiento de reservas.
- **Configuración de Dominio**: Uso de `reservayasamuel.es` para acceso vía Cloudflare y configuración del Proxy Apache para responder a dicho nombre.

## Documentación Adicional
- **Documentación Final**: Ver `DOCUMENTACION_FINAL.md` para un análisis detallado del proyecto.
- **Guía de Presentación**: Ver `PRESENTACION_GUIADA.md` para el esquema del nuevo PowerPoint solicitado.
