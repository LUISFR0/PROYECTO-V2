<?php
include('../../config.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    session_start();
    $_SESSION['mensaje'] = '❌ Cliente no válido';
    $_SESSION['icono'] = 'error';
    header("Location: ../../../clientes/clientes.php");
    exit;
}

$sql = "DELETE FROM clientes WHERE id_cliente = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

session_start();
$_SESSION['mensaje'] = '✅ Cliente eliminado correctamente';
$_SESSION['icono'] = 'success';

header("Location: ../../../clientes/locales.php");
