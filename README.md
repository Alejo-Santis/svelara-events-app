# Svelara Events App

Sistema completo de gestión de eventos desarrollado con Laravel 12, Svelte 5, Inertia.js y PostgreSQL.

## 🚀 Stack Tecnológico

### Backend
- **Laravel 12** - Framework PHP
- **PostgreSQL** - Base de datos
- **Spatie Laravel Permission** - Sistema de roles y permisos
- **Inertia.js** - Bridge Laravel-Svelte

### Frontend
- **Svelte 5** - Framework JavaScript reactivo
- **Tailwind CSS** - Framework de estilos
- **Vite** - Build tool

## ✨ Características Principales

### Sistema de Roles
- **Super Admin** - Control total del sistema
- **Organizador** - Crear y gestionar eventos
- **Asistente** - Registrarse y asistir a eventos
- **Guest** - Ver eventos públicos

### Gestión de Eventos
- ✅ Eventos públicos y privados
- ✅ Eventos gratuitos y de pago
- ✅ Sistema de capacidad y lista de espera
- ✅ Categorías y etiquetas
- ✅ Galería de imágenes
- ✅ Eventos online y presenciales

### Sistema de Tickets
- ✅ Generación automática de tickets con QR
- ✅ Check-in con escaneo de QR
- ✅ Tickets únicos por asistente

### Sistema de Pagos
- 💳 Integración con múltiples gateways:
  - Stripe
  - PayPal
  - MercadoPago
- ✅ Sistema de reembolsos
- ✅ Historial de transacciones

### Invitaciones
- ✅ Sistema de invitaciones con tokens únicos
- ✅ Eventos privados por invitación
- ✅ Tracking de invitaciones (pendiente, aceptada, rechazada)

### Notificaciones
- 📧 Email (confirmaciones, recordatorios)
- 🔔 In-app notifications
- 📱 Push notifications (futuro)

### Activity Logs
- 📊 Seguimiento completo de acciones
- 🔍 Auditoría del sistema
- 📝 Logs detallados con metadata

## 📦 Estructura de Base de Datos

- `users` - Usuarios del sistema
- `events` - Eventos
- `categories` - Categorías de eventos
- `tags` - Etiquetas
- `event_user` - Asistentes (tabla pivote)
- `tickets` - Tickets con QR
- `payments` - Pagos procesados
- `event_images` - Galería de imágenes
- `event_invitations` - Invitaciones a eventos privados
- `activity_logs` - Logs de actividad del sistema
- `notifications` - Notificaciones

## 🛠️ Instalación

```bash
# Clonar repositorio
git clone https://github.com/tu-usuario/svelara-events-app.git
cd svelara-events-app

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Copiar archivo de configuración
cp .env.example .env

# Generar key de aplicación
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=event_management
DB_USERNAME=postgres
DB_PASSWORD=tu_password

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (opcional)
php artisan db:seed

# Crear enlace simbólico para storage
php artisan storage:link

# Compilar assets
npm run dev
```

## 🚦 Uso

### Desarrollo
```bash
# Terminal 1 - Servidor Laravel
php artisan serve

# Terminal 2 - Compilación de assets
npm run dev
```

### Producción
```bash
# Compilar assets para producción
npm run build

# Optimizar aplicación
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📋 Características por Implementar

### Fase 1 - Core
- [x] Sistema de autenticación
- [x] CRUD de eventos
- [x] Sistema de roles y permisos
- [x] Registro a eventos
- [x] Sistema de tickets

### Fase 2 - Avanzado
- [ ] Integración de pagos
- [ ] Sistema de notificaciones completo
- [ ] Check-in con QR
- [ ] Dashboard de organizador
- [ ] Panel de administración

### Fase 3 - Mejoras
- [ ] Búsqueda avanzada y filtros
- [ ] Estadísticas y reportes
- [ ] Exportación de datos
- [ ] API pública
- [ ] PWA

## 🔐 Seguridad

- ✅ CSRF Protection
- ✅ XSS Prevention
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ Rate Limiting
- ✅ Password Hashing (Bcrypt)
- ✅ Email Verification
- ✅ Role-based Access Control

## 📊 Características Técnicas

- **UUIDs** en rutas para mayor seguridad
- **Soft Deletes** en eventos
- **Activity Logging** automático
- **Eager Loading** para optimizar queries
- **Scopes** para queries reutilizables
- **Policies** para autorización
- **Form Requests** para validación

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT.

## 👤 Autor

**Tu Nombre**
- GitHub: [@Alejo-Santis](https://github.com/Alejo-Santis)

## 🙏 Agradecimientos

- Laravel Framework
- Svelte Community
- Spatie Laravel Permission
- Inertia.js Team
