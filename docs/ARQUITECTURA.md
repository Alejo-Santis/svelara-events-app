# 🎯 Arquitectura del Sistema de Gestión de Eventos

**Proyecto:** Event Management System  
**Stack:** Laravel 12+ | Svelte 5 | Inertia.js | Bootstrap 5  
**Fecha:** Noviembre 2025  
**Versión:** 1.0.0

---

## 📋 Tabla de Contenidos

1. [Visión General](#visión-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Stack Tecnológico](#stack-tecnológico)
4. [Sistema de Roles y Permisos](#sistema-de-roles-y-permisos)
5. [Estructura de Base de Datos](#estructura-de-base-de-datos)
6. [Flujos de Usuario](#flujos-de-usuario)
7. [Módulos del Sistema](#módulos-del-sistema)
8. [Estructura de Archivos](#estructura-de-archivos)
9. [Seguridad](#seguridad)
10. [Consideraciones de Performance](#consideraciones-de-performance)

---

## 🎯 Visión General

### Objetivo
Sistema completo de gestión de eventos que permite crear, administrar y participar en eventos tanto públicos como privados, con soporte para eventos gratuitos y de pago.

### Alcance del MVP

#### Funcionalidades Principales:
- ✅ Gestión completa de eventos (CRUD)
- ✅ Sistema de eventos públicos y privados
- ✅ Registro y cancelación de asistencia
- ✅ Sistema de roles (Admin, Organizador, Asistente)
- ✅ Eventos gratuitos y de pago
- ✅ Sistema de tickets con QR
- ✅ Notificaciones (Email, In-app, Push)
- ✅ Dashboard para cada tipo de usuario
- ✅ Búsqueda y filtrado de eventos
- ✅ Estadísticas para organizadores
- ✅ Check-in de asistentes

#### Fuera del MVP Inicial:
- ❌ Multiidioma
- ❌ Geolocalización
- ❌ Sistema de chat/mensajería
- ❌ Streaming de eventos virtuales

---

## 🏗️ Arquitectura del Sistema

### Arquitectura General

```
┌─────────────────────────────────────────────────────────────┐
│                     CAPA DE PRESENTACIÓN                     │
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Landing    │  │  Dashboard   │  │  Organizador │     │
│  │   Pública    │  │   Usuario    │  │   Dashboard  │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│         │                  │                  │             │
│         └──────────────────┴──────────────────┘             │
│                            │                                 │
│                  ┌─────────▼─────────┐                      │
│                  │   Svelte 5 +      │                      │
│                  │   Bootstrap 5     │                      │
│                  └─────────┬─────────┘                      │
└────────────────────────────┼──────────────────────────────┘
                             │
                    ┌────────▼────────┐
                    │   Inertia.js    │
                    └────────┬────────┘
                             │
┌────────────────────────────▼──────────────────────────────┐
│                    CAPA DE APLICACIÓN                      │
│                                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐ │
│  │Controllers│  │ Services │  │  Policies │  │  Events  │ │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘ │
│                                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐ │
│  │   Jobs   │  │ Listeners│  │   Mails  │  │ Resources│ │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘ │
└────────────────────────────┬────────────────────────────┘
                             │
┌────────────────────────────▼──────────────────────────────┐
│                     CAPA DE DOMINIO                        │
│                                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐ │
│  │  Models  │  │Migrations│  │Seeders   │  │Factories │ │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘ │
└────────────────────────────┬────────────────────────────┘
                             │
┌────────────────────────────▼──────────────────────────────┐
│                   CAPA DE PERSISTENCIA                     │
│                                                             │
│  ┌──────────────────────────────────────────────────────┐ │
│  │              Base de Datos MySQL                      │ │
│  └──────────────────────────────────────────────────────┘ │
│                                                             │
│  ┌──────────────────────────────────────────────────────┐ │
│  │              Cache (Redis) - Opcional                 │ │
│  └──────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

### Patrón de Diseño

**Arquitectura MVC Mejorada con:**
- Repository Pattern (opcional para lógica compleja)
- Service Layer para lógica de negocio
- Event-Driven para notificaciones
- Policy-Based Authorization

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework:** Laravel 12+
- **PHP:** 8.3+
- **Base de Datos:** MySQL 8.0+
- **Cache:** Redis (opcional, recomendado para producción)
- **Queue:** Database/Redis
- **Autenticación:** Laravel Breeze + Inertia
- **Autorización:** Spatie Laravel Permission

### Frontend
- **Framework JS:** Svelte 5
- **Bridge:** Inertia.js
- **CSS Framework:** Bootstrap 5
- **Build Tool:** Vite
- **Iconos:** Bootstrap Icons / Font Awesome

### Servicios Externos
- **Email:** SMTP / Mailgun / SendGrid
- **Pagos:** Stripe / PayPal / MercadoPago
- **Storage:** Local / S3 (para imágenes de eventos)
- **Notificaciones Push:** Firebase Cloud Messaging (opcional)

### Herramientas de Desarrollo
- **Version Control:** Git
- **Dependency Management:** Composer, NPM
- **Testing:** PHPUnit, Pest
- **Code Quality:** PHP CS Fixer, Laravel Pint

---

## 👥 Sistema de Roles y Permisos

### Roles Definidos

```php
┌─────────────────────────────────────────────────────────┐
│ SUPER_ADMIN (Admin)                                     │
├─────────────────────────────────────────────────────────┤
│ - Control total del sistema                             │
│ - Gestión de usuarios y roles                           │
│ - Gestión de todos los eventos                          │
│ - Acceso a reportes globales                            │
│ - Configuración del sistema                             │
│ - Gestión de categorías                                 │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ORGANIZER (Organizador)                                 │
├─────────────────────────────────────────────────────────┤
│ - Crear eventos públicos y privados                     │
│ - Editar/Eliminar sus propios eventos                   │
│ - Gestionar asistentes de sus eventos                   │
│ - Realizar check-in de asistentes                       │
│ - Ver estadísticas de sus eventos                       │
│ - Crear eventos gratuitos y de pago                     │
│ - Enviar invitaciones a eventos privados                │
│ - Exportar listas de asistentes                         │
│ - Todas las capacidades de ATTENDEE                     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ATTENDEE (Asistente/Usuario)                            │
├─────────────────────────────────────────────────────────┤
│ - Ver eventos públicos                                  │
│ - Registrarse a eventos gratuitos                       │
│ - Comprar entradas a eventos de pago                    │
│ - Cancelar su asistencia                                │
│ - Ver sus tickets con QR                                │
│ - Acceder a eventos privados (con invitación)           │
│ - Ver historial de eventos                              │
│ - Gestionar notificaciones                              │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ GUEST (Visitante sin registro)                          │
├─────────────────────────────────────────────────────────┤
│ - Ver listado de eventos públicos                       │
│ - Buscar y filtrar eventos                              │
│ - Ver detalles de eventos públicos                      │
│ - NO puede registrarse a eventos                        │
└─────────────────────────────────────────────────────────┘
```

### Permisos del Sistema

```php
// EVENTOS - CRUD
'view_public_events'        // Todos
'view_private_events'       // Con invitación
'view_any_events'           // Admin
'create_events'             // Organizer, Admin
'edit_own_events'           // Organizer (sus eventos)
'edit_any_events'           // Admin
'delete_own_events'         // Organizer (sus eventos)
'delete_any_events'         // Admin
'publish_events'            // Organizer, Admin
'create_paid_events'        // Organizer, Admin

// ASISTENCIA
'register_to_events'        // Attendee, Organizer
'cancel_registration'       // Attendee, Organizer
'view_own_attendees'        // Organizer (sus eventos)
'view_any_attendees'        // Admin
'checkin_attendees'         // Organizer (sus eventos), Admin

// USUARIOS
'view_users'                // Admin
'create_users'              // Admin
'edit_users'                // Admin
'delete_users'              // Admin
'assign_roles'              // Admin

// CATEGORÍAS
'manage_categories'         // Admin

// TICKETS Y PAGOS
'manage_tickets'            // Organizer (sus eventos), Admin
'view_own_sales'            // Organizer (sus eventos)
'view_any_sales'            // Admin
'refund_tickets'            // Organizer (sus eventos), Admin

// REPORTES
'view_own_stats'            // Organizer
'view_global_stats'         // Admin

// NOTIFICACIONES
'send_notifications'        // Organizer (a sus asistentes), Admin
'manage_notification_settings' // Todos (propias)

// CONFIGURACIÓN
'manage_settings'           // Admin
```

---

## 🗄️ Estructura de Base de Datos

### Diagrama de Relaciones

```
┌──────────────┐         ┌──────────────┐         ┌──────────────┐
│    users     │────┬───│    events    │────┬───│  categories  │
└──────────────┘    │    └──────────────┘    │    └──────────────┘
       │            │           │             │
       │            │           │             │
       │     ┌──────▼──────┐   │      ┌──────▼──────┐
       │     │event_user   │   │      │  event_tag  │
       │     │(attendees)  │   │      └──────┬──────┘
       │     └─────────────┘   │             │
       │                       │      ┌──────▼──────┐
       │     ┌─────────────┐   │      │    tags     │
       │     │   tickets   │◄──┤      └─────────────┘
       │     └─────────────┘   │
       │                       │
       │     ┌─────────────┐   │
       └────►│model_has_   │   │
             │roles/perms  │   │
             └─────────────┘   │
                               │
             ┌─────────────┐   │
             │event_images │◄──┤
             └─────────────┘   │
                               │
             ┌─────────────┐   │
             │notifications│◄──┤
             └─────────────┘   │
                               │
             ┌─────────────┐   │
             │event_       │◄──┤
             │invitations  │   │
             └─────────────┘   │
                               │
             ┌─────────────┐   │
             │   payments  │◄──┘
             └─────────────┘
```

### Tablas Principales

#### 1. users
```sql
Descripción: Usuarios del sistema
Tipo: Tabla principal

Campos:
- id (bigint, PK, auto_increment)
- name (varchar 255)
- email (varchar 255, unique)
- email_verified_at (timestamp, nullable)
- password (varchar 255)
- phone (varchar 20, nullable)
- avatar (varchar 255, nullable)
- bio (text, nullable)
- is_active (boolean, default true)
- remember_token (varchar 100, nullable)
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- UNIQUE (email)
- INDEX (is_active)

Relaciones:
- hasMany: events (como creador)
- belongsToMany: events (como asistente) through event_user
- hasMany: tickets
- hasMany: notifications
- hasMany: payments
```

#### 2. events
```sql
Descripción: Eventos del sistema
Tipo: Tabla principal

Campos:
- id (bigint, PK, auto_increment)
- user_id (bigint, FK -> users.id) [Creador del evento]
- category_id (bigint, FK -> categories.id, nullable)
- title (varchar 255)
- slug (varchar 255, unique)
- description (text)
- short_description (varchar 500, nullable)
- start_date (datetime)
- end_date (datetime)
- timezone (varchar 50, default 'America/Bogota')
- location (varchar 255, nullable) [Dirección física]
- venue_name (varchar 255, nullable) [Nombre del lugar]
- is_online (boolean, default false)
- online_url (varchar 255, nullable)
- is_public (boolean, default true)
- is_published (boolean, default false)
- is_free (boolean, default true)
- price (decimal 10,2, default 0.00)
- currency (varchar 3, default 'COP')
- capacity (integer, nullable) [Capacidad máxima]
- allow_waitlist (boolean, default false)
- registration_deadline (datetime, nullable)
- featured_image (varchar 255, nullable)
- status (enum: 'draft', 'published', 'cancelled', 'completed', default 'draft')
- views_count (integer, default 0)
- deleted_at (timestamp, nullable) [Soft delete]
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- UNIQUE (slug)
- INDEX (user_id)
- INDEX (category_id)
- INDEX (is_public)
- INDEX (is_published)
- INDEX (status)
- INDEX (start_date)
- FULLTEXT (title, description) [Para búsqueda]

Relaciones:
- belongsTo: user (creador)
- belongsTo: category
- belongsToMany: users (asistentes) through event_user
- belongsToMany: tags through event_tag
- hasMany: tickets
- hasMany: event_images
- hasMany: event_invitations
- hasMany: payments
```

#### 3. categories
```sql
Descripción: Categorías de eventos
Tipo: Catálogo

Campos:
- id (bigint, PK, auto_increment)
- name (varchar 100)
- slug (varchar 100, unique)
- description (text, nullable)
- icon (varchar 50, nullable) [Clase de icono Bootstrap]
- color (varchar 7, nullable) [Color hexadecimal]
- is_active (boolean, default true)
- order (integer, default 0)
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- UNIQUE (slug)
- INDEX (is_active)
- INDEX (order)

Relaciones:
- hasMany: events
```

#### 4. tags
```sql
Descripción: Etiquetas para clasificar eventos
Tipo: Catálogo

Campos:
- id (bigint, PK, auto_increment)
- name (varchar 50)
- slug (varchar 50, unique)
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- UNIQUE (slug)

Relaciones:
- belongsToMany: events through event_tag
```

#### 5. event_user (attendees)
```sql
Descripción: Relación entre eventos y asistentes
Tipo: Tabla pivote

Campos:
- id (bigint, PK, auto_increment)
- event_id (bigint, FK -> events.id)
- user_id (bigint, FK -> users.id)
- status (enum: 'registered', 'cancelled', 'attended', 'waitlist', default 'registered')
- checked_in_at (timestamp, nullable)
- checked_in_by (bigint, FK -> users.id, nullable) [Quién hizo el check-in]
- registration_date (timestamp, default CURRENT_TIMESTAMP)
- cancellation_date (timestamp, nullable)
- cancellation_reason (text, nullable)
- notes (text, nullable)

Índices:
- PRIMARY KEY (id)
- INDEX (event_id, user_id) [Compuesto]
- INDEX (status)
- UNIQUE (event_id, user_id) [Un usuario no puede registrarse dos veces]

Relaciones:
- belongsTo: event
- belongsTo: user
- belongsTo: checked_in_by_user (user)
```

#### 6. event_tag
```sql
Descripción: Relación entre eventos y etiquetas
Tipo: Tabla pivote

Campos:
- event_id (bigint, FK -> events.id)
- tag_id (bigint, FK -> tags.id)

Índices:
- PRIMARY KEY (event_id, tag_id)
- INDEX (tag_id)

Relaciones:
- belongsTo: event
- belongsTo: tag
```

#### 7. tickets
```sql
Descripción: Tickets/Entradas de eventos
Tipo: Tabla principal

Campos:
- id (bigint, PK, auto_increment)
- event_id (bigint, FK -> events.id)
- user_id (bigint, FK -> users.id)
- ticket_number (varchar 50, unique) [Número único de ticket]
- qr_code (text) [Código QR en base64 o ruta]
- status (enum: 'active', 'used', 'cancelled', 'refunded', default 'active')
- price_paid (decimal 10,2, default 0.00)
- payment_id (bigint, FK -> payments.id, nullable)
- used_at (timestamp, nullable)
- issued_at (timestamp, default CURRENT_TIMESTAMP)
- expires_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- UNIQUE (ticket_number)
- INDEX (event_id)
- INDEX (user_id)
- INDEX (payment_id)
- INDEX (status)

Relaciones:
- belongsTo: event
- belongsTo: user
- belongsTo: payment
```

#### 8. event_images
```sql
Descripción: Galería de imágenes de eventos
Tipo: Tabla de medios

Campos:
- id (bigint, PK, auto_increment)
- event_id (bigint, FK -> events.id)
- path (varchar 255)
- filename (varchar 255)
- mime_type (varchar 50)
- size (integer) [Tamaño en bytes]
- is_featured (boolean, default false)
- order (integer, default 0)
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- INDEX (event_id)
- INDEX (is_featured)
- INDEX (order)

Relaciones:
- belongsTo: event
```

#### 9. event_invitations
```sql
Descripción: Invitaciones a eventos privados
Tipo: Tabla principal

Campos:
- id (bigint, PK, auto_increment)
- event_id (bigint, FK -> events.id)
- email (varchar 255)
- token (varchar 100, unique)
- invited_by (bigint, FK -> users.id)
- user_id (bigint, FK -> users.id, nullable) [Si el invitado se registra]
- status (enum: 'pending', 'accepted', 'rejected', 'expired', default 'pending')
- sent_at (timestamp, default CURRENT_TIMESTAMP)
- accepted_at (timestamp, nullable)
- expires_at (timestamp)
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- UNIQUE (token)
- INDEX (event_id)
- INDEX (email)
- INDEX (status)

Relaciones:
- belongsTo: event
- belongsTo: inviter (user)
- belongsTo: user (invitado)
```

#### 10. payments
```sql
Descripción: Pagos de entradas
Tipo: Tabla principal

Campos:
- id (bigint, PK, auto_increment)
- user_id (bigint, FK -> users.id)
- event_id (bigint, FK -> events.id)
- payment_method (varchar 50) [stripe, paypal, mercadopago]
- transaction_id (varchar 255, unique) [ID del proveedor de pago]
- amount (decimal 10,2)
- currency (varchar 3, default 'COP')
- status (enum: 'pending', 'completed', 'failed', 'refunded', default 'pending')
- payment_date (timestamp, nullable)
- refund_date (timestamp, nullable)
- refund_reason (text, nullable)
- metadata (json, nullable) [Datos adicionales del pago]
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- UNIQUE (transaction_id)
- INDEX (user_id)
- INDEX (event_id)
- INDEX (status)
- INDEX (payment_date)

Relaciones:
- belongsTo: user
- belongsTo: event
- hasMany: tickets
```

#### 11. notifications
```sql
Descripción: Notificaciones del sistema
Tipo: Tabla principal

Campos:
- id (uuid, PK)
- type (varchar 255) [Clase de la notificación]
- notifiable_type (varchar 255) [Polimórfico]
- notifiable_id (bigint) [Polimórfico]
- data (json)
- read_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)

Índices:
- PRIMARY KEY (id)
- INDEX (notifiable_type, notifiable_id)
- INDEX (read_at)

Relaciones:
- morphTo: notifiable (generalmente User)
```

### Tablas Spatie Permission

#### 12. roles
```sql
- id (bigint, PK)
- name (varchar 255)
- guard_name (varchar 255)
- created_at (timestamp)
- updated_at (timestamp)
```

#### 13. permissions
```sql
- id (bigint, PK)
- name (varchar 255)
- guard_name (varchar 255)
- created_at (timestamp)
- updated_at (timestamp)
```

#### 14. model_has_roles
```sql
- role_id (bigint, FK)
- model_type (varchar 255)
- model_id (bigint)
```

#### 15. model_has_permissions
```sql
- permission_id (bigint, FK)
- model_type (varchar 255)
- model_id (bigint)
```

#### 16. role_has_permissions
```sql
- permission_id (bigint, FK)
- role_id (bigint, FK)
```

---

## 🔄 Flujos de Usuario

### 1. Flujo de Registro de Usuario

```
[Usuario] → [Visita landing]
    ↓
[Click "Registrarse"]
    ↓
[Formulario registro] → [Validación]
    ↓
[Crear cuenta] → [Enviar email verificación]
    ↓
[Usuario verifica email]
    ↓
[Login automático] → [Dashboard]
    ↓
[Asignar rol: ATTENDEE por defecto]
```

### 2. Flujo de Creación de Evento (Organizador)

```
[Organizador] → [Dashboard]
    ↓
[Click "Crear Evento"]
    ↓
[Formulario evento]:
    - Datos básicos (nombre, descripción, fecha)
    - Tipo (público/privado)
    - Precio (gratis/pago)
    - Capacidad
    - Categoría
    - Imágenes
    ↓
[Guardar como borrador] OR [Publicar]
    ↓
[Si es privado] → [Generar invitaciones]
    ↓
[Evento creado] → [Notificación confirmación]
    ↓
[Si publicado] → [Visible en listado público]
```

### 3. Flujo de Registro a Evento Gratuito

```
[Usuario] → [Explora eventos]
    ↓
[Selecciona evento]
    ↓
[Ver detalles]
    ↓
[Click "Registrarse"]
    ↓
[Si NO está logueado] → [Redirigir a login/registro]
    ↓
[Si evento público] → [Verificar capacidad]
    ↓
    ├─ [Hay cupo] → [Registrar asistencia]
    │                    ↓
    │               [Crear registro en event_user]
    │                    ↓
    │               [Generar ticket con QR]
    │                    ↓
    │               [Enviar email confirmación]
    │                    ↓
    │               [Redirigir a "Mis Eventos"]
    │
    └─ [Sin cupo] → [Agregar a lista de espera] (si está habilitada)
                        ↓
                   [Notificar usuario]
```

### 4. Flujo de Compra de Entrada (Evento de Pago)

```
[Usuario] → [Selecciona evento de pago]
    ↓
[Ver detalles + Precio]
    ↓
[Click "Comprar Entrada"]
    ↓
[Si NO está logueado] → [Redirigir a login/registro]
    ↓
[Seleccionar cantidad] (si aplica)
    ↓
[Resumen de compra]
    ↓
[Seleccionar método de pago]
    ↓
[Redirigir a pasarela de pago]
    ↓
[Procesar pago]:
    ├─ [Pago exitoso]
    │       ↓
    │   [Crear registro en payments]
    │       ↓
    │   [Crear registro en event_user]
    │       ↓
    │   [Generar ticket(s) con QR]
    │       ↓
    │   [Enviar email con ticket]
    │       ↓
    │   [Redirigir a "Mis Tickets"]
    │
    └─ [Pago fallido]
            ↓
        [Notificar error]
            ↓
        [Permitir reintentar]
```

### 5. Flujo de Check-in en Evento

```
[Asistente llega al evento]
    ↓
[Muestra QR del ticket]
    ↓
[Organizador escanea QR]:
    ↓
[Verificar ticket]:
    ├─ [Ticket válido]
    │       ↓
    │   [Marcar como "attended" en event_user]
    │       ↓
    │   [Actualizar checked_in_at]
    │       ↓
    │   [Marcar ticket como "used"]
    │       ↓
    │   [Mostrar confirmación: "✓ Check-in exitoso"]
    │
    └─ [Ticket inválido/usado]
            ↓
        [Mostrar error: "✗ Ticket ya usado" o "✗ Ticket inválido"]
```

### 6. Flujo de Invitación a Evento Privado

```
[Organizador] → [Crea evento privado]
    ↓
[Click "Enviar Invitaciones"]
    ↓
[Formulario invitaciones]:
    - Lista de emails
    - Mensaje personalizado (opcional)
    ↓
[Generar tokens únicos]
    ↓
[Crear registros en event_invitations]
    ↓
[Enviar emails con link único]
    ↓
[Invitado recibe email]
    ↓
[Click en link con token]
    ↓
[Validar token]:
    ├─ [Token válido]
    │       ↓
    │   [Mostrar página del evento]
    │       ↓
    │   [Si NO está logueado] → [Login/Registro con email pre-llenado]
    │       ↓
    │   [Registrar asistencia automáticamente]
    │       ↓
    │   [Actualizar invitation status: 'accepted']
    │       ↓
    │   [Generar ticket]
    │
    └─ [Token inválido/expirado]
            ↓
        [Mostrar error]
```

### 7. Flujo de Cancelación de Asistencia

```
[Usuario] → [Mis Eventos]
    ↓
[Selecciona evento registrado]
    ↓
[Click "Cancelar Asistencia"]
    ↓
[Confirmación]:
    - "¿Estás seguro?"
    - Motivo (opcional)
    ↓
[Confirmar cancelación]
    ↓
[Actualizar event_user]:
    - status: 'cancelled'
    - cancellation_date: now()
    - cancellation_reason: texto
    ↓
[Si evento de pago]:
    ├─ [Dentro del período de reembolso]
    │       ↓
    │   [Procesar reembolso]
    │       ↓
    │   [Actualizar payment status: 'refunded']
    │       ↓
    │   [Notificar reembolso procesado]
    │
    └─ [Fuera del período]
            ↓
        [Cancelar sin reembolso]
            ↓
        [Notificar sin reembolso]
    ↓
[Invalidar ticket]
    ↓
[Si hay lista de espera] → [Notificar siguiente en lista]
    ↓
[Enviar email confirmación cancelación]
```

---

## 📦 Módulos del Sistema

### Módulo: Autenticación
**Responsabilidad:** Gestión de usuarios y sesiones

**Componentes:**
- Login
- Registro
- Recuperación de contraseña
- Verificación de email
- Perfil de usuario

**Rutas:**
```
/login
/register
/forgot-password
/reset-password/{token}
/verify-email/{id}/{hash}
/profile
/profile/edit
```

### Módulo: Eventos (Público)
**Responsabilidad:** Navegación y descubrimiento de eventos públicos

**Componentes:**
- Landing page
- Listado de eventos
- Búsqueda y filtros
- Detalle de evento
- Registro a evento

**Rutas:**
```
/
/eventos
/eventos/buscar?q={query}
/eventos/{slug}
/eventos/{slug}/registrar
```

### Módulo: Dashboard Usuario
**Responsabilidad:** Panel personal del asistente

**Componentes:**
- Dashboard principal
- Mis eventos registrados
- Mis tickets
- Historial de eventos
- Configuración de notificaciones

**Rutas:**
```
/dashboard
/mis-eventos
/mis-tickets
/historial
/configuracion/notificaciones
```

### Módulo: Dashboard Organizador
**Responsabilidad:** Gestión de eventos propios

**Componentes:**
- Dashboard de organizador
- Crear evento
- Editar evento
- Gestionar asistentes
- Check-in de asistentes
- Estadísticas del evento
- Enviar invitaciones

**Rutas:**
```
/organizador/dashboard
/organizador/eventos/crear
/organizador/eventos/{id}/editar
/organizador/eventos/{id}/asistentes
/organizador/eventos/{id}/checkin
/organizador/eventos/{id}/estadisticas
/organizador/eventos/{id}/invitaciones
```

### Módulo: Panel Admin
**Responsabilidad:** Administración completa del sistema

**Componentes:**
- Dashboard admin
- Gestión de usuarios
- Gestión de todos los eventos
- Gestión de categorías
- Gestión de roles y permisos
- Reportes globales
- Configuración del sistema

**Rutas:**
```
/admin/dashboard
/admin/usuarios
/admin/eventos
/admin/categorias
/admin/roles
/admin/permisos
/admin/reportes
/admin/configuracion
```

### Módulo: Notificaciones
**Responsabilidad:** Sistema de notificaciones multicanal

**Tipos de notificaciones:**
1. **Email:**
   - Confirmación de registro a evento
   - Recordatorio de evento (24h antes)
   - Cancelación de evento
   - Check-in exitoso
   - Invitación a evento privado

2. **In-app:**
   - Nuevos eventos en categorías favoritas
   - Cambios en eventos registrados
   - Mensajes del organizador

3. **Push (opcional - Fase 2):**
   - Recordatorios urgentes
   - Inicio de evento

### Módulo: Pagos
**Responsabilidad:** Procesamiento de pagos y tickets

**Componentes:**
- Carrito de compra
- Checkout
- Confirmación de pago
- Generación de tickets
- Reembolsos

**Integraciones:**
- Stripe
- PayPal
- MercadoPago

---

## 📁 Estructura de Archivos del Proyecto

```
proyecto-eventos/
│
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── SendEventReminders.php
│   │       └── CleanupExpiredInvitations.php
│   │
│   ├── Events/
│   │   ├── EventCreated.php
│   │   ├── EventUpdated.php
│   │   ├── EventCancelled.php
│   │   ├── UserRegisteredToEvent.php
│   │   ├── UserCancelledRegistration.php
│   │   ├── TicketGenerated.php
│   │   └── PaymentProcessed.php
│   │
│   ├── Exceptions/
│   │   ├── EventCapacityReachedException.php
│   │   ├── InvalidTicketException.php
│   │   └── PaymentFailedException.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── PasswordResetController.php
│   │   │   │
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── EventController.php
│   │   │   │   └── SearchController.php
│   │   │   │
│   │   │   ├── Dashboard/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── MyEventsController.php
│   │   │   │   ├── TicketController.php
│   │   │   │   └── AttendanceController.php
│   │   │   │
│   │   │   ├── Organizer/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── EventController.php
│   │   │   │   ├── AttendeeController.php
│   │   │   │   ├── CheckinController.php
│   │   │   │   ├── InvitationController.php
│   │   │   │   └── StatsController.php
│   │   │   │
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── UserController.php
│   │   │       ├── EventController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── RoleController.php
│   │   │       └── ReportController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── EnsureUserIsOrganizer.php
│   │   │   ├── EnsureUserIsAdmin.php
│   │   │   └── CheckEventOwnership.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── StoreEventRequest.php
│   │   │   ├── UpdateEventRequest.php
│   │   │   ├── RegisterToEventRequest.php
│   │   │   └── StoreInvitationRequest.php
│   │   │
│   │   └── Resources/
│   │       ├── EventResource.php
│   │       ├── UserResource.php
│   │       ├── TicketResource.php
│   │       ├── CategoryResource.php
│   │       └── AttendeeResource.php
│   │
│   ├── Jobs/
│   │   ├── SendEventReminder.php
│   │   ├── SendEventInvitation.php
│   │   ├── GenerateEventTicket.php
│   │   ├── ProcessPayment.php
│   │   └── SendEventCancellationNotice.php
│   │
│   ├── Listeners/
│   │   ├── SendEventConfirmationEmail.php
│   │   ├── NotifyOrganizerOfNewRegistration.php
│   │   ├── GenerateTicketQRCode.php
│   │   └── UpdateEventStatistics.php
│   │
│   ├── Mail/
│   │   ├── EventRegistrationConfirmation.php
│   │   ├── EventReminder.php
│   │   ├── EventCancellationNotice.php
│   │   ├── EventInvitation.php
│   │   └── TicketGenerated.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Event.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   │   ├── Ticket.php
│   │   ├── EventImage.php
│   │   ├── EventInvitation.php
│   │   ├── Payment.php
│   │   └── Notification.php
│   │
│   ├── Notifications/
│   │   ├── EventReminderNotification.php
│   │   ├── EventUpdatedNotification.php
│   │   ├── NewEventInCategoryNotification.php
│   │   └── CheckinSuccessNotification.php
│   │
│   ├── Policies/
│   │   ├── EventPolicy.php
│   │   ├── UserPolicy.php
│   │   └── TicketPolicy.php
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   │
│   └── Services/
│       ├── EventService.php
│       ├── TicketService.php
│       ├── PaymentService.php
│       ├── QRCodeService.php
│       └── NotificationService.php
│
├── bootstrap/
│   ├── app.php
│   └── cache/
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── mail.php
│   ├── payment.php (custom)
│   └── permission.php (Spatie)
│
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── EventFactory.php
│   │   ├── CategoryFactory.php
│   │   └── TicketFactory.php
│   │
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_02_000000_create_categories_table.php
│   │   ├── 2024_01_03_000000_create_tags_table.php
│   │   ├── 2024_01_04_000000_create_events_table.php
│   │   ├── 2024_01_05_000000_create_event_user_table.php
│   │   ├── 2024_01_06_000000_create_event_tag_table.php
│   │   ├── 2024_01_07_000000_create_tickets_table.php
│   │   ├── 2024_01_08_000000_create_event_images_table.php
│   │   ├── 2024_01_09_000000_create_event_invitations_table.php
│   │   ├── 2024_01_10_000000_create_payments_table.php
│   │   ├── 2024_01_11_000000_create_notifications_table.php
│   │   └── 2024_01_12_000000_create_permission_tables.php (Spatie)
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleAndPermissionSeeder.php
│       ├── CategorySeeder.php
│       ├── UserSeeder.php
│       └── EventSeeder.php
│
├── public/
│   ├── build/ (Vite build output)
│   ├── storage/ (symlink)
│   └── index.php
│
├── resources/
│   ├── css/
│   │   ├── app.css
│   │   └── bootstrap-custom.scss
│   │
│   ├── js/
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   │
│   │   ├── Components/
│   │   │   ├── Shared/
│   │   │   │   ├── Navbar.svelte
│   │   │   │   ├── Footer.svelte
│   │   │   │   ├── Alert.svelte
│   │   │   │   ├── Modal.svelte
│   │   │   │   ├── Pagination.svelte
│   │   │   │   └── LoadingSpinner.svelte
│   │   │   │
│   │   │   ├── Event/
│   │   │   │   ├── EventCard.svelte
│   │   │   │   ├── EventList.svelte
│   │   │   │   ├── EventFilters.svelte
│   │   │   │   ├── EventDetail.svelte
│   │   │   │   └── EventForm.svelte
│   │   │   │
│   │   │   ├── Ticket/
│   │   │   │   ├── TicketCard.svelte
│   │   │   │   └── QRCodeDisplay.svelte
│   │   │   │
│   │   │   └── Dashboard/
│   │   │       ├── StatsCard.svelte
│   │   │       ├── EventChart.svelte
│   │   │       └── AttendeesList.svelte
│   │   │
│   │   ├── Layouts/
│   │   │   ├── PublicLayout.svelte
│   │   │   ├── DashboardLayout.svelte
│   │   │   ├── OrganizerLayout.svelte
│   │   │   └── AdminLayout.svelte
│   │   │
│   │   └── Pages/
│   │       ├── Public/
│   │       │   ├── Home.svelte
│   │       │   ├── Events/Index.svelte
│   │       │   ├── Events/Show.svelte
│   │       │   └── Search.svelte
│   │       │
│   │       ├── Auth/
│   │       │   ├── Login.svelte
│   │       │   ├── Register.svelte
│   │       │   └── ForgotPassword.svelte
│   │       │
│   │       ├── Dashboard/
│   │       │   ├── Index.svelte
│   │       │   ├── MyEvents.svelte
│   │       │   ├── Tickets.svelte
│   │       │   └── Profile.svelte
│   │       │
│   │       ├── Organizer/
│   │       │   ├── Dashboard.svelte
│   │       │   ├── Events/Index.svelte
│   │       │   ├── Events/Create.svelte
│   │       │   ├── Events/Edit.svelte
│   │       │   ├── Attendees.svelte
│   │       │   ├── Checkin.svelte
│   │       │   └── Stats.svelte
│   │       │
│   │       └── Admin/
│   │           ├── Dashboard.svelte
│   │           ├── Users.svelte
│   │           ├── Events.svelte
│   │           ├── Categories.svelte
│   │           └── Reports.svelte
│   │
│   └── views/
│       └── app.blade.php (Layout principal para Inertia)
│
├── routes/
│   ├── web.php
│   ├── api.php (si es necesario)
│   └── console.php
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── events/ (imágenes de eventos)
│   │   │   ├── avatars/ (avatares de usuarios)
│   │   │   └── qrcodes/ (códigos QR)
│   │   └── private/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   │   ├── EventTest.php
│   │   ├── RegistrationTest.php
│   │   ├── TicketTest.php
│   │   └── PaymentTest.php
│   │
│   └── Unit/
│       ├── EventServiceTest.php
│       ├── TicketServiceTest.php
│       └── QRCodeServiceTest.php
│
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js (si usas Tailwind además de Bootstrap)
├── phpunit.xml
└── README.md
```

---

## 🔒 Seguridad

### Medidas de Seguridad Implementadas

1. **Autenticación:**
   - Hash de contraseñas con Bcrypt
   - Protección CSRF en todos los formularios
   - Verificación de email obligatoria
   - Rate limiting en login (5 intentos por minuto)

2. **Autorización:**
   - Políticas de Laravel para cada modelo
   - Middleware de roles y permisos (Spatie)
   - Verificación de propiedad de recursos

3. **Validación:**
   - Form Requests para todas las entradas
   - Validación de tipos de datos
   - Sanitización de inputs
   - XSS Protection

4. **Base de Datos:**
   - Prepared Statements (Eloquent)
   - Soft Deletes para recuperación
   - Índices en campos sensibles
   - Backup automático diario

5. **Archivos:**
   - Validación de tipos MIME
   - Límite de tamaño de archivos
   - Almacenamiento fuera del webroot
   - Nombres aleatorios para archivos

6. **APIs/Pagos:**
   - HTTPS obligatorio en producción
   - Tokens de API seguros
   - Validación de webhooks
   - Logs de transacciones

7. **Sesiones:**
   - Sesiones encriptadas
   - Cookies seguras (HttpOnly, Secure, SameSite)
   - Regeneración de sesión en login
   - Timeout de sesión

---

## ⚡ Consideraciones de Performance

### Optimizaciones Implementadas

1. **Base de Datos:**
   - Índices en campos frecuentemente consultados
   - Eager Loading de relaciones
   - Paginación de resultados
   - Cache de consultas frecuentes

2. **Frontend:**
   - Lazy loading de componentes Svelte
   - Optimización de imágenes (WebP, responsive)
   - Minificación de CSS/JS (Vite)
   - CDN para assets estáticos

3. **Cache:**
   - Cache de listado público de eventos (15 min)
   - Cache de categorías y tags
   - Cache de estadísticas (5 min)
   - Cache de configuración del sistema

4. **Colas:**
   - Envío de emails en background
   - Generación de QR codes en background
   - Procesamiento de pagos asíncrono

5. **Monitoreo:**
   - Laravel Telescope (desarrollo)
   - Logs estructurados
   - Métricas de performance
   - Alertas de errores

---

## 📊 Métricas y Reportes

### Métricas para Organizadores

- Total de eventos creados
- Total de asistentes registrados
- Tasa de asistencia real vs registrados
- Ingresos por evento (si es de pago)
- Eventos más populares
- Distribución de asistentes por evento
- Gráfico de registros en el tiempo

### Métricas para Administradores

- Total de usuarios por rol
- Total de eventos activos
- Eventos por categoría
- Ingresos totales del sistema
- Usuarios más activos
- Eventos más populares globalmente
- Tasa de conversión (visitantes → registros)

---

## 🚀 Deployment

### Requisitos del Servidor

- **PHP:** 8.3+
- **MySQL:** 8.0+
- **Node.js:** 18+ (para build)
- **Composer:** 2.x
- **Redis:** 6+ (recomendado)
- **Supervisor:** Para colas (recomendado)

### Variables de Entorno Críticas

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eventos
DB_USERNAME=usuario
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525

STRIPE_KEY=tu_stripe_key
STRIPE_SECRET=tu_stripe_secret

QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

## 📝 Próximos Pasos

1. ✅ Documentar arquitectura (ESTE ARCHIVO)
2. ⏭️ Crear migraciones de base de datos
3. ⏭️ Crear modelos y relaciones
4. ⏭️ Configurar Spatie Permissions
5. ⏭️ Implementar seeders
6. ⏭️ Crear controladores base
7. ⏭️ Implementar autenticación con Breeze
8. ⏭️ Crear layouts Svelte
9. ⏭️ Desarrollar páginas públicas
10. ⏭️ Implementar dashboard de usuario
11. ⏭️ Implementar dashboard de organizador
12. ⏭️ Implementar panel de admin
13. ⏭️ Sistema de notificaciones
14. ⏭️ Integración de pagos
15. ⏭️ Testing
16. ⏭️ Deployment

---

## 📞 Contacto y Soporte

**Desarrollador:** [Tu Nombre]  
**Email:** [tu-email@ejemplo.com]  
**Repositorio:** [URL del repositorio]

---

**Última actualización:** Noviembre 2025  
**Versión del documento:** 1.0.0
