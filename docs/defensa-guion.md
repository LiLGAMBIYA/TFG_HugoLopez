# Guion de Defensa - JM Motor

## 1. Introducción - 2 min

- Problema: gestión manual de citas, vehículos y servicios en taller.
- Solución: plataforma web JM Motor para clientes y administrador.
- Objetivo: centralizar citas, vehículos, catálogo de servicios, piezas y facturación.

## 2. Arquitectura y tecnologías - 3 min

- Symfony 8 con patrón MVC.
- Doctrine ORM y migraciones.
- Symfony Security con roles y hashing.
- Twig como motor de plantillas.
- Bootstrap 5 y CSS responsive.
- Docker Compose con Nginx, PHP-FPM y PostgreSQL.

## 3. Modelo de datos - 2 min

- Usuario: clientes y administradores.
- Vehiculo: asociado a propietario.
- Cita: núcleo funcional con servicio, estado, cliente, vehículo y operario.
- Servicio, Pieza, CitaPieza y Factura.

## 4. Demo práctica - 6 min

1. Página pública y catálogo de servicios.
2. Registro/login de cliente.
3. Solicitud de cita.
4. Gestión de mis citas y cancelación.
5. Gestión de mis vehículos.
6. Login admin y gestión de citas/servicios/piezas/usuarios.

## 5. Conclusiones - 2 min

- Aprendizaje de Symfony, Doctrine, seguridad y Docker.
- Mejora de organización de código y despliegue reproducible.
- Trabajo futuro: Tailwind completo, PDF de facturas y notificaciones.
