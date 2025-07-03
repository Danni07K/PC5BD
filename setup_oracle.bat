@echo off
echo Configurando Oracle Database para Biblioteca Virtual...
echo.

echo 1. Configurando variables de entorno...
echo DB_CONNECTION=oracle > .env
echo DB_HOST=localhost >> .env
echo DB_PORT=1521 >> .env
echo DB_DATABASE=XE >> .env
echo DB_USERNAME=biblioteca_user >> .env
echo DB_PASSWORD=tu_contraseña_aqui >> .env
echo DB_CHARSET=AL32UTF8 >> .env
echo DB_PREFIX= >> .env
echo DB_SCHEMA_PREFIX= >> .env
echo DB_EDITION=ora$base >> .env
echo DB_SERVER_VERSION=11g >> .env

echo.
echo 2. Limpiando caché de configuración...
php artisan config:clear
php artisan cache:clear

echo.
echo 3. Probando conexión a Oracle...
php artisan test:oracle --connection=oracle

echo.
echo Configuración completada!
echo.
echo IMPORTANTE: Edita el archivo .env y cambia:
echo - DB_USERNAME=biblioteca_user
echo - DB_PASSWORD=tu_contraseña_aqui
echo.
echo Por tus credenciales reales de Oracle.
echo.
pause
