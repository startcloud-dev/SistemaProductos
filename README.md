# Sistema de Registro de Productos

Un sistema web de registro de productos desarrollado con PHP, JavaScript (Vanilla), HTML/CSS y PostgreSQL, utilizando una arquitectura MVC (Modelo-Vista-Controlador).

# Versiones
PHP 8.5.6
PostgreSQL 18

## Requisitos Previos

Para ejecutar este proyecto en otro PC, necesitarás tener instalado lo siguiente:

1. **Servidor Web con PHP** (PHP 7.4 o superior): Puedes usar [XAMPP](https://www.apachefriends.org/), WAMP, o el servidor integrado de PHP.
2. **PostgreSQL**: Motor de base de datos relacional. ([Descargar PostgreSQL](https://www.postgresql.org/download/)).
3. **pgAdmin** o **DBeaver** (Opcional pero recomendado): Para ejecutar los scripts y administrar la base de datos visualmente.

---

## Paso 1: Configurar la Base de Datos

1. Abre **pgAdmin** (o tu cliente de base de datos preferido).
2. Crea una nueva base de datos llamada `SistemaProductos`.
3. Abre el archivo `sql/SqlProductos.sql` que viene incluido en la carpeta del proyecto.
4. Ejecuta todo el script SQL en la base de datos que acabas de crear. 
   - Esto generará automáticamente todas las tablas (`bodega`, `sucursal`, `moneda`, `material`, `productos`, `productosmateriales`).
   - También insertará los datos iniciales necesarios para que los desplegables (combobox) y checkboxes funcionen.

---

## Paso 2: Configurar la Conexión a la Base de Datos en PHP

Asegúrate de que las credenciales del código coincidan con la instalación de PostgreSQL de la nueva computadora.

1. Abre el archivo `config/Conexion.php`.
2. Verifica y modifica las siguientes variables si es necesario:
   ```php
   private $host = "localhost";
   private $port = 5432;
   private $dbname = "SistemaProductos";
   private $user = "postgres"; // Cambia esto por tu usuario de PostgreSQL
   private $password = "admin"; // Cambia esto por tu contraseña de PostgreSQL
   ```

> **⚠️ Importante para usuarios de XAMPP/WAMP:** 
> Debes asegurarte de que PHP tenga habilitada la extensión de PostgreSQL. Abre tu archivo `php.ini`, busca y quítale el punto y coma (`;`) al inicio a las siguientes líneas:
> `extension=pdo_pgsql`
> `extension=pgsql`
> Luego reinicia Apache.

---

## Paso 3: Ejecutar la Aplicación

Tienes dos opciones principales para levantar el proyecto:

### Opción A: Servidor integrado de PHP (Más rápido)
1. Abre una terminal (CMD o PowerShell) y navega hasta la carpeta raíz del proyecto.
2. Ejecuta este comando:
   ```bash
   php -S localhost:8000
   ```
3. Abre tu navegador y visita: [http://localhost:8000/vistas/index.html](http://localhost:8000/vistas/index.html)

### Opción B: Usando XAMPP / WAMP
1. Copia toda la carpeta del proyecto dentro del directorio de tu servidor web local:
   - Para XAMPP: Cópiala en `C:\xampp\htdocs\ProyectoPHPPrueba`
   - Para WAMP: Cópiala en `C:\wamp64\www\ProyectoPHPPrueba`
2. Inicia el servicio de **Apache** desde el Panel de Control.
3. Abre tu navegador y visita: `http://localhost/ProyectoPHPPrueba/vistas/index.html`

---

## Estructura del Proyecto

- `config/`: Contiene la clase de conexión a la BD y el `Enrutador.php` (API gateway).
- `controladores/`: Contiene la lógica del negocio (`productoControlador.php`).
- `css/`: Archivos de estilos visuales.
- `js/`: Lógica del lado del cliente y validaciones (`scriptProducto.js`).
- `modelos/`: Clases abstractas y entidades de la base de datos (`Producto.php`).
- `sql/`: Scripts de inicialización de la base de datos.
- `vistas/`: Archivo principal del formulario (`index.html`).
