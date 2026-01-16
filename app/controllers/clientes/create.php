<?php
include('../../config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ".$URL."/clientes/create.php");
    exit;
}

$tipo_cliente = $_POST['tipo_cliente'] ?? null;
$nombre       = $_POST['nombre_completo'] ?? null;
$telefono     = $_POST['telefono'] ?? null;
$calle        = $_POST['calle_numero'] ?? null;
$colonia      = $_POST['colonia'] ?? null;
$municipio    = $_POST['municipio'] ?? null;
$estado       = $_POST['estado'] ?? null;
$cp           = $_POST['cp'] ?? null;
$referencias  = trim($_POST['referencias'] ?? null);

if (
    !$tipo_cliente || !$nombre || !$telefono ||
    !$calle || !$colonia || !$municipio || !$estado || !$cp
) {
    $_SESSION['mensaje'] = "❌ Faltan datos obligatorios";
    $_SESSION['icono']   = "error";
    header("Location: ".$URL."/clientes/create.php");
    exit;
}

try {
    $sql = "INSERT INTO clientes 
    (tipo_cliente, nombre_completo, telefono, calle_numero, colonia, municipio, estado, cp, referencias)
    VALUES (?,?,?,?,?,?,?,?,?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $tipo_cliente,
        $nombre,
        $telefono,
        $calle,
        $colonia,
        $municipio,
        $estado,
        $cp,
        $referencias
    ]);

    $_SESSION['mensaje'] = "✅ Cliente registrado correctamente";
    $_SESSION['icono']   = "success";

    header("Location: ".$URL."/clientes/create.php");
    exit;

} catch (Exception $e) {
    $_SESSION['mensaje'] = "❌ Error al guardar el cliente";
    $_SESSION['icono']   = "error";
    header("Location: ".$URL."/clientes/create.php");
    exit;
}
