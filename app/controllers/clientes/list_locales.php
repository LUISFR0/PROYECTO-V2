<?php
// ESTE ARCHIVO SOLO TRAE DATOS

$sql = "SELECT *
        FROM clientes
        WHERE tipo_cliente = 'local'
        ";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$clientes_locales = $stmt->fetchAll(PDO::FETCH_ASSOC);
