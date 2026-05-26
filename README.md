# JM Motor - Gestión de Taller Mecánico

Aplicación web desarrollada con Symfony para la gestión de citas, vehículos, servicios, piezas, usuarios y facturación de un taller mecánico.

## Stack técnico

- Backend: Symfony 8, PHP 8.4, Doctrine ORM, Symfony Security y Symfony Forms.
- Frontend: Twig como motor de plantillas, Bootstrap 5 por CDN y CSS organizado en `public/css/app.css`.
- Base de datos: PostgreSQL 16 en Docker.
- Despliegue local reproducible: Docker Compose con Nginx, PHP-FPM y PostgreSQL.

## Puesta en marcha con Docker

El tribunal puede levantar el proyecto con:

```bash
docker compose up -d --build
```

La aplicación quedará disponible en:

```text
http://localhost:8080
```

El contenedor `app` ejecuta automáticamente:

1. `composer install`
2. `php bin/console doctrine:migrations:migrate --no-interaction`
3. `php bin/console app:load-demo-data --no-interaction`
4. `php bin/console cache:clear --no-warmup`

## Credenciales de prueba

### Administrador

- Email: `admin@taller.com`
- Contraseña: `admin123`

### Cliente

- Email: `cliente@taller.com`
- Contraseña: `cliente123`

## Funcionalidades principales

- Registro y login de usuarios.
- Gestión de roles `ROLE_USER` y `ROLE_ADMIN`.
- Solicitud pública de citas.
- Gestión de citas por cliente y administración.
- Estados de cita: `Pendiente`, `Confirmada`, `Realizada`, `Cancelada`.
- Gestión de vehículos del cliente.
- Administración de servicios, piezas, usuarios y facturación.
- Protección CSRF en formularios sensibles.
- Hashing de contraseñas mediante Symfony Security.

## Comandos útiles

```bash
docker compose exec app php bin/console doctrine:migrations:status
docker compose exec app php bin/console app:load-demo-data
docker compose exec app php bin/console lint:twig templates
docker compose exec app php bin/console lint:container
```

## Preparación de entrega

Para entregar, comprimir el proyecto excluyendo:

- `vendor/`
- `node_modules/`
- `var/cache/`
- `var/log/`
- `.git/`

Ejemplo:

```bash
git archive --format zip --output entrega-jmmotor.zip HEAD
```
