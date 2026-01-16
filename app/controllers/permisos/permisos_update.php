<?php

$id_rol = $_GET['id'] ?? null;
if (!$id_rol) {
    header("Location: " . $URL . "/roles");
    exit;
}

/* =========================
   DATOS DEL ROL
========================= */
$sqlRol = "SELECT * FROM tb_roles WHERE id_rol = :id_rol";
$qRol = $pdo->prepare($sqlRol);
$qRol->bindParam(':id_rol', $id_rol);
$qRol->execute();
$rol = $qRol->fetch(PDO::FETCH_ASSOC);

/* =========================
   PERMISOS DEL ROL
========================= */
$sqlPermRol = "
    SELECT id_permiso 
    FROM tb_roles_permisos
    WHERE id_rol = :id_rol
";
$qPermRol = $pdo->prepare($sqlPermRol);
$qPermRol->bindParam(':id_rol', $id_rol);
$qPermRol->execute();

$permisos_rol = $qPermRol->fetchAll(PDO::FETCH_COLUMN);
