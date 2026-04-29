# Ejercicio de Entrega Parcial TFG: Módulo de Autenticación

**Autor:** Hugo López (y Manuel Prieto Macias)
**Proyecto:** Trabajo de Fin de Grado - Desarrollo de Plataforma
**Fecha:** 27 de Abril de 2026

## Introducción
Este documento consolida la primera entrega parcial del Trabajo de Fin de Grado (TFG), presentando el esqueleto inicial y funcional de la aplicación. Específicamente, se ha implementado el módulo de autenticación de usuarios (Registro y Login) así como la página de inicio (Home), garantizando su correcta conexión con la base de datos relacional elegida.

---

## 1. Capturas de Pantalla de la Página de Inicio (Home)

La página de inicio establece el punto de partida de la aplicación, renderizada a través de Twig e integrando el framework CSS Bootstrap importado en `base.html.twig`. El enrutamiento está configurado bajo la ruta `/home`.

> **[📝 INSTRUCCIÓN PARA EL ALUMNO]**
> Inserta aquí una captura de pantalla de la página de inicio mostrando el `base.html.twig` funcionando (ej: localhost:8000/home).
> 
> *Ejemplo:*
> ![Página de Inicio](ruta/a/tu/captura-home.png)

---

## 2. Capturas de Pantalla del Formulario de Registro

El registro está protegido y validado, incluyendo medidas de seguridad y verificación de la longitud y estructura de las contraseñas.

> **[📝 INSTRUCCIÓN PARA EL ALUMNO]**
> Inserta aquí las siguientes capturas:
> 1. El formulario vacío (`/register`).
> 2. El formulario con datos válidos antes de enviar.
> 3. Un mensaje de éxito tras un registro correcto (o la redirección al login).
> 4. *(Opcional)* Un mensaje de error si el correo ya existe o las contraseñas no coinciden.
>
> *Ejemplos:*
> ![Formulario de Registro Vacío](ruta/a/tu/captura-registro-vacio.png)
> ![Formulario de Registro con Error](ruta/a/tu/captura-registro-error.png)

---

## 3. Capturas de Pantalla del Formulario de Login

El inicio de sesión hace uso de la configuración de seguridad (`security.yaml`) que proporciona Symfony de forma nativa mediante el `form_login`.

> **[📝 INSTRUCCIÓN PARA EL ALUMNO]**
> Inserta aquí las siguientes capturas:
> 1. El formulario vacío (`/login`).
> 2. El formulario con credenciales válidas antes de enviar.
> 3. La página de inicio mostrando que el usuario ha iniciado sesión (ej. con el mensaje "Hola, [email]" en el navbar).
> 4. *(Opcional)* Mensaje de error (ej. "Credenciales inválidas").
>
> *Ejemplos:*
> ![Formulario de Login Vacío](ruta/a/tu/captura-login-vacio.png)
> ![Sesión Iniciada en Home](ruta/a/tu/captura-login-exito.png)

---

## 4. Evidencia de Conexión con la Base de Datos

El proyecto se sirve de una base de datos PostgreSQL gestionada mediante un contenedor Docker, orquestado a través del fichero `compose.yaml`.

> **[📝 INSTRUCCIÓN PARA EL ALUMNO]**
> Inserta aquí una captura de tu cliente de base de datos (DBeaver, pgAdmin, línea de comandos de psql) donde se vea la tabla `usuario` y el nuevo registro insertado.
>
> *Ejemplo:*
> ![Tabla Usuarios Base de Datos](ruta/a/tu/captura-db.png)

---

## 5. Breve Descripción Técnica

La implementación se ha realizado apoyándose en las siguientes tecnologías y estructuras:

1. **Entidades Doctrine (`User.php` / `Usuario.php`)**:
   - Se ha utilizado la entidad `Usuario`, la cual implementa las interfaces `UserInterface` y `PasswordAuthenticatedUserInterface`. 
   - Define propiedades como `id`, `email`, `roles`, y `password`. El correo electrónico se utiliza como identificador visual de usuario (`getUserIdentifier()`) y garantiza la unicidad a través del constraint `#[ORM\UniqueConstraint]`.

2. **Controladores y Rutas (`#[Route]`)**:
   - **`HomeController`**: Define la ruta `#[Route('/home', name: 'app_home')]` encargada de devolver la vista principal.
   - **`RegistrationController`**: Define la ruta `/register`. Gestiona el envío del `RegistrationFormType`. Se ha inyectado el servicio `UserPasswordHasherInterface` para garantizar el cifrado seguro (hash) de la contraseña en texto plano antes de persistir la entidad mediante Doctrine (`EntityManagerInterface`).
   - **`SecurityController`**: Expone la ruta `/login` gestionando la autenticación interceptada por el firewall de Symfony, y la ruta `/logout` para invalidar la sesión del usuario.

3. **Plantilla `base.html.twig` e Integración Visual**:
   - Se ha consolidado una plantilla maestra (`base.html.twig`) que importa **Bootstrap 5** vía CDN.
   - Incluye una barra de navegación (Navbar) que es sensible al estado de autenticación del usuario. Haciendo uso de la variable global de Twig `app.user`, el menú adapta sus opciones, mostrando "Iniciar Sesión" y "Registrarse" a los visitantes anónimos, y un saludo junto a la opción "Cerrar Sesión" para los usuarios autenticados.
   - Las vistas de cada controlador (`home/index.html.twig`, `registration/register.html.twig` y `security/login.html.twig`) extienden de `base.html.twig` reemplazando únicamente el bloque `{% block body %}`.

4. **Infraestructura (`compose.yaml` y `.env`)**:
   - Se configura un servicio `database` de PostgreSQL utilizando la imagen `postgres:16-alpine`.
   - El mapeo de puertos (`5432:5432`) permite conectarse a la BBDD desde clientes locales, mientras que en `.env` la variable `DATABASE_URL` orquesta la cadena de conexión de Doctrine hacia dicho contenedor.

---

## 6. Píldoras de Investigación (Aspectos Adicionales)

Para demostrar una profundización en el framework, se han investigado los siguientes elementos:

- **Seguridad en Contraseñas**: Symfony incorpora hashes modernos por defecto de forma agnóstica. Al definir el algoritmo en modo `auto` en el `security.yaml` (`algorithm: auto`), Symfony utiliza internamente algoritmos como **Argon2i**, **Argon2id** o **Bcrypt**, migrando automáticamente los hashes más antiguos cuando un usuario inicia sesión. Estos algoritmos implementan tanto *hashing* iterativo como *salting* automático, lo cual previene ataques como los basados en *Rainbow Tables*.
- **Autenticación de Dos Factores (2FA)**: Para añadir 2FA en Symfony, una de las soluciones más maduras es el bundle `scheb/two-factor-bundle`. Su integración permite solicitar un segundo factor (ej. código TOTP de Google Authenticator o email) tras superar con éxito la primera capa (usuario y contraseña).
- **Manejo de Sesiones y Tokens**: Las sesiones clásicas guardan el estado en el servidor (vinculado por la *cookie* de sesión). Para integraciones con SPA (React, Vue, Angular) o APIs, se recomienda el uso de **JSON Web Tokens (JWT)** empleando librerías como `LexikJWTAuthenticationBundle`. El JWT es "Stateless", lo que significa que en lugar de buscar la sesión en el servidor en cada petición, el propio token contiene toda la información firmada criptográficamente (claims).
