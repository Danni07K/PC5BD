<?php
// Script para probar conexión PDO a Oracle manualmente

$dsn = 'oci:dbname=//localhost:1521/XEPDB1;charset=AL32UTF8';
$username = 'biblioteca_user';
$password = 'biblio123';

try {
    echo "Intentando conectar a Oracle via PDO...\n";
    $pdo = new PDO($dsn, $username, $password);
    echo "✅ Conexión exitosa a Oracle via PDO!\n";
    $stmt = $pdo->query('SELECT 1 as test FROM dual');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Consulta de prueba: ";
    print_r($row);
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}
