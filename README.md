
# Demo API: Configuración y Funcionalidades

## Resumen General

Este proyecto es una demostración de una API construida con Laravel 11, que incluye:
- **Gestión de Posts y Usuarios**: CRUD completo.
- **Notificaciones Automáticas por Email**: Envío de correos electrónicos a los administradores cuando se crea un nuevo post.
- **Integración con Brave SMTP**: Configuración de un servicio SMTP personalizado para el envío de correos.
- **Pruebas Unitarias**: Validación automatizada de las funcionalidades clave, incluidas las notificaciones.

## Funcionalidades Detalladas

### 1. Gestión de Usuarios y Posts
- **CRUD de Usuarios**: Permite crear, leer, actualizar y eliminar usuarios.
- **CRUD de Posts**: Similar al de usuarios, pero incluye notificaciones por correo al administrador.

### 2. Notificaciones Automáticas
- Se genera un correo automático para los administradores cada vez que se crea un post.
- **Plantilla del Correo**:
  - **Asunto**: Nuevo Post Publicado.
  - **Cuerpo**: Detalles del post (título y creador).

### 3. Configuración de Notificaciones
- Se utilizó Brave SMTP para gestionar el envío de correos.
- La configuración incluye:
  - Servidor SMTP: `smtp-relay.brave.com`.
  - Puerto: `587`.
  - Autenticación habilitada.
  - Importante configura un email a el usuario admin para que le prueba del smtp sea corecta

### 4. Comando Personalizado
- **`php artisan service:init`**: Comando para inicializar el servicio.
  - Crea las tablas necesarias (por ejemplo, `jobs` para colas).
  - Configura `.env` automáticamente para establecer `QUEUE_CONNECTION=database`.

### 5. APIs Disponibles
- **Crear Post**: `POST /api/posts`
- **Listar Posts**: `GET /api/posts`
- **Actualizar Post**: `PUT /api/posts/{id}`
- **Eliminar Post**: `DELETE /api/posts/{id}`

### 6. Pruebas Unitarias
- Validación del envío de notificaciones con `Notification::fake()`.
- Prueba: `php artisan test --filter=PostNotificationTest`.
- Esta prueba se limita a revisar el corecto funcionamiento del codigo mas no del servicio smtp

## Proceso de Configuración

1. **Clonar el Repositorio**:
   ```bash
   git clone https://github.com/luydevops/demo-api.git
   cd demo-api
   ```

2. **Instalar Dependencias**:
   ```bash
   composer install
   ```

3. **Configurar el Archivo `.env`**:
   - Copiar `.env.example` a `.env`:
     ```bash
     cp .env.example .env
     ```
   - Agregar las credenciales SMTP:
     ```env
     MAIL_MAILER=smtp
     MAIL_HOST=smtp-relay.brave.com
     MAIL_PORT=587
     MAIL_USERNAME=your-email@domain.com
     MAIL_PASSWORD=your-password
     MAIL_ENCRYPTION=tls
     ```

4. **Generar Clave de Aplicación**:
   ```bash
   php artisan key:generate
   ```

5. **Configurar Base de Datos**:
   - Actualiza las variables de conexión a la base de datos en el archivo `.env`.

6. **Ejecutar Migraciones**:
   ```bash
   php artisan migrate
   ```

7. **Inicializar el Servicio**:
   ```bash
   php artisan service:init
   ```

8. **Ejecutar el Worker de Cola** (opcional para notificaciones en cola):
   ```bash
   php artisan queue:work
   ```

9. **Probar la Aplicación**:
   - Ejecuta las pruebas unitarias para verificar que todo funcione correctamente:
     ```bash
     php artisan test
     ```

---

## Contribuciones
¡Las contribuciones son bienvenidas! Por favor, envía tus PRs al repositorio.

## Licencia
El proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).
