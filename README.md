# Sistema de Préstamo de Libros - Biblioteca Virtual

Aplicación web para la gestión de préstamos de libros en una biblioteca, desarrollada con Laravel y Oracle.

## Descripción general
Permite registrar usuarios, libros, préstamos, devoluciones y consultar reportes de libros más prestados y préstamos vencidos. La lógica de negocio principal se implementa en PL/SQL y se invoca desde Laravel.

## Pasos para ejecutar la aplicación

### 1. Requisitos previos
- Tener instalado **PHP 8.x** o superior.
- Tener instalado **Composer**.
- Tener instalado **Node.js** y **npm**.
- Tener instalado **Oracle Database** (por ejemplo, Oracle XE).
- Tener instalado el driver **Yajra/laravel-oci8** para Laravel.
- Tener creado el usuario y la base de datos Oracle (por ejemplo, `biblioteca_user`).

### 2. Clonar el repositorio o copiar el proyecto
```bash
git clone <URL_DEL_REPOSITORIO>
cd nueva-biblioteca
```

### 3. Instalar dependencias de PHP
```bash
composer install
```

### 4. Instalar dependencias de frontend
```bash
npm install
npm run build
```

### 5. Configurar el archivo `.env`
Copia el archivo de ejemplo y edítalo:
```bash
cp .env.example .env
```
Edita las siguientes variables según tu entorno Oracle:
```bash
DB_CONNECTION=oracle
DB_HOST=127.0.0.1
DB_PORT=1521
DB_DATABASE=XEPDB1
DB_USERNAME=biblioteca_user
DB_PASSWORD=biblio123
```

### 6. Configurar la conexión Oracle en `config/database.php`
Asegúrate de que la conexión `oracle` esté bien configurada para Yajra, por ejemplo:
```php
'oracle' => [
    'driver'         => 'oracle',
    'tns'            => '',
    'host'           => env('DB_HOST', '127.0.0.1'),
    'port'           => env('DB_PORT', '1521'),
    'database'       => env('DB_DATABASE', 'XEPDB1'),
    'username'       => env('DB_USERNAME', 'biblioteca_user'),
    'password'       => env('DB_PASSWORD', 'biblio123'),
    'charset'        => 'AL32UTF8',
    'prefix'         => '',
]
```

### 7. Crear las tablas y lógica en Oracle
Conéctate a Oracle como `biblioteca_user` y ejecuta los scripts:
```sql
@D:/BD5/nueva-biblioteca/sql/oracle_schema.sql
@D:/BD5/nueva-biblioteca/sql/loan_procedures.sql
```
Esto creará las tablas, vistas y procedimientos necesarios.

### 8. Generar la clave de la aplicación
```bash
php artisan key:generate
```

### 9. Levantar el servidor de desarrollo
```bash
php artisan serve
```
La aplicación estará disponible en [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 10. ¡Listo!
- Puedes acceder, registrar usuarios, agregar libros, registrar préstamos y consultar reportes.
- Si necesitas datos de ejemplo, puedes agregarlos manualmente desde la interfaz.

---

## Créditos y Licencia

Este proyecto utiliza el framework Laravel, que es software de código abierto licenciado bajo la [licencia MIT](https://opensource.org/licenses/MIT).

Para más información sobre Laravel, visita la [documentación oficial](https://laravel.com/docs).

---
