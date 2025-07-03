# Configuración de Oracle Database para Biblioteca Virtual

## Requisitos Previos

### 1. Instalar Oracle Database
- Descargar e instalar Oracle Database Express Edition (XE) o superior
- Configurar un usuario con permisos para crear tablas y procedimientos

### 2. Instalar extensión PHP oci8
Para Windows con XAMPP:
1. Descargar `php_oci8.dll` para PHP 8.2 desde: https://pecl.php.net/package/oci8
2. Colocar el archivo en `C:\xampp\php\ext\`
3. Editar `C:\xampp\php\php.ini` y agregar: `extension=oci8`
4. Reiniciar Apache

Para Linux:
```bash
sudo apt-get install php-oci8
# o
sudo yum install php-oci8
```

### 3. Instalar paquete Laravel para Oracle
```bash
composer require yajra/laravel-oci8:"^9.0"
```

## Configuración

### 1. Variables de entorno (.env)
```env
# Base de datos actual (sqlite para desarrollo, oracle para producción)
DB_CONNECTION=oracle

# Configuración Oracle
DB_HOST=localhost
DB_PORT=1521
DB_DATABASE=XE
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
DB_CHARSET=AL32UTF8
DB_PREFIX=
DB_SCHEMA_PREFIX=
DB_EDITION=ora$base
DB_SERVER_VERSION=11g
```

### 2. Crear esquema de base de datos
Ejecutar el archivo `sql/oracle_schema.sql` en Oracle SQL Developer o SQL*Plus:

```sql
-- Conectar como usuario con permisos de administrador
CONNECT system/password@localhost:1521/XE

-- Ejecutar el script
@sql/oracle_schema.sql
```

### 3. Crear procedimientos almacenados
Ejecutar el archivo `sql/loan_procedures.sql`:

```sql
-- Conectar como usuario de la aplicación
CONNECT biblioteca_user/password@localhost:1521/XE

-- Ejecutar el script
@sql/loan_procedures.sql
```

### 4. Otorgar permisos
```sql
-- Como administrador
GRANT EXECUTE ON loan_package TO biblioteca_user;
GRANT SELECT ON v_overdue_loans TO biblioteca_user;
GRANT SELECT ON v_most_borrowed_books TO biblioteca_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON users TO biblioteca_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON books TO biblioteca_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON loans TO biblioteca_user;
```

## Uso

### Desarrollo (SQLite)
El proyecto funciona con SQLite por defecto. Para desarrollo:
```env
DB_CONNECTION=sqlite
```

### Producción (Oracle)
Para usar Oracle en producción:
```env
DB_CONNECTION=oracle
```

### Servicios disponibles
El `OracleService` maneja automáticamente la conexión:

```php
use App\Services\OracleService;

// Crear préstamo
$result = OracleService::createLoan($userId, $bookId, $dueDate, $notes);

// Devolver libro
$result = OracleService::returnBook($loanId, $returnDate, $notes);

// Obtener préstamos vencidos
$overdue = OracleService::getOverdueLoans();

// Obtener libros más prestados
$mostBorrowed = OracleService::getMostBorrowedBooks(10);
```

## Verificación

### 1. Probar conexión
```bash
php artisan tinker
```
```php
DB::connection('oracle')->getPdo();
```

### 2. Probar procedimientos
```php
// En tinker
$result = App\Services\OracleService::createLoan(1, 1, '2024-12-31', 'Test');
dd($result);
```

## Troubleshooting

### Error: "ext-oci8 is missing"
- Instalar la extensión oci8 para PHP
- Verificar que esté habilitada en php.ini

### Error: "ORA-12541: TNS:no listener"
- Verificar que Oracle esté ejecutándose
- Verificar puerto y configuración de red

### Error: "ORA-00942: table or view does not exist"
- Ejecutar los scripts SQL para crear las tablas
- Verificar permisos del usuario

### Error: "ORA-00955: name is already being used"
- Los objetos ya existen, continuar con la configuración 
