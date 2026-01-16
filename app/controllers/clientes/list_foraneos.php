<?php
// ESTE ARCHIVO SOLO TRAE DATOS

$sql = "SELECT *
        FROM clientes
        WHERE tipo_cliente = 'foraneo'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$clientes_foraneos = $stmt->fetchAll(PDO::FETCH_ASSOC);
