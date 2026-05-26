# Memoria Técnica - JM Motor

## 1. Descripción del proyecto

JM Motor es una aplicación web para la gestión integral de un taller mecánico. Permite a clientes solicitar citas y gestionar sus vehículos, mientras que el administrador puede controlar citas, servicios, piezas, usuarios y facturas.

## 2. Objetivos y alcance

- Digitalizar el proceso de solicitud y gestión de citas.
- Mantener un catálogo de servicios con precios y descripciones.
- Gestionar vehículos asociados a clientes.
- Controlar piezas y facturación desde el área administrativa.
- Proporcionar un entorno reproducible mediante Docker.

## 3. Arquitectura del sistema

Arquitectura MVC basada en Symfony:

- Controladores: reciben peticiones HTTP y coordinan formularios/vistas.
- Entidades Doctrine: modelan el dominio del taller.
- Repositorios: encapsulan consultas a base de datos.
- Twig: genera las vistas HTML con una plantilla base común.
- PostgreSQL: almacena los datos persistentes.
- Nginx + PHP-FPM: sirven la aplicación en contenedores.

## 4. Modelo de datos

Entidades principales:

- Usuario: autenticación, roles y relación con citas/vehículos.
- Vehiculo: matrícula, marca, modelo, VIN y propietario.
- Cita: descripción, fecha deseada, estado, servicio, cliente, vehículo y operario.
- Servicio: nombre, descripción y precio.
- Pieza: referencia, nombre, precio unitario y stock.
- CitaPieza: relación entre citas y piezas usadas.
- Factura: número, fecha, base imponible, IVA, total y cita asociada.

## 5. Seguridad

- Contraseñas hasheadas con `UserPasswordHasherInterface`.
- Firewall principal con login por formulario y CSRF.
- Control de acceso por rutas en `security.yaml`.
- Separación entre `ROLE_USER` y `ROLE_ADMIN`.
- Validación de propietario antes de cancelar citas o eliminar vehículos.

## 6. Frontend y UX

Se usa Twig por integración directa con Symfony Forms y el patrón MVC del proyecto. La interfaz se estructura mediante `base.html.twig` y plantillas jerárquicas por módulo. El diseño es responsive y utiliza Bootstrap 5 junto a CSS organizado en `public/css/app.css`.

Nota sobre Tailwind CSS: el enunciado exige Tailwind CSS, pero el proyecto original ya estaba implementado con Bootstrap 5. Si se requiere cumplimiento literal, debe migrarse el sistema visual a Tailwind o integrarlo mediante pipeline frontend antes de la entrega final.

## 7. Despliegue con Docker

El despliegue se realiza con:

```bash
docker compose up -d --build
```

Servicios:

- `web`: Nginx en puerto 8080.
- `app`: PHP 8.4-FPM con Composer y extensiones necesarias.
- `database`: PostgreSQL 16.

El arranque ejecuta migraciones y carga datos de prueba automáticamente.

## 8. Datos de prueba

Credenciales:

- Admin: `admin@taller.com` / `admin123`
- Cliente: `cliente@taller.com` / `cliente123`

## 9. Trabajo futuro

- Migración completa a Tailwind CSS para cumplir estrictamente el requisito visual.
- Generación automática de PDF de facturas.
- Notificaciones por email/SMS.
- Panel de estadísticas para administración.
