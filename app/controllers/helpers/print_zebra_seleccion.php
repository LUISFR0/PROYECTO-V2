<?php
include('../../config.php');

/* ==========================
   RECIBIR IDS
========================== */
$ids = $_GET['ids'] ?? '';
if (!$ids) {
    die('No se seleccionaron códigos');
}

$ids_array = array_map('intval', explode(',', $ids));
$in = str_repeat('?,', count($ids_array) - 1) . '?';

/* ==========================
   CONSULTAR STOCK
========================== */
$sql = "SELECT 
            s.codigo_unico,
            p.nombre AS nombre_producto
        FROM stock s
        INNER JOIN tb_almacen p ON p.id_producto = s.id_producto
        WHERE s.id_stock IN ($in)
        ORDER BY s.id_stock ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($ids_array);
$stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$stocks) {
    die('No se encontraron datos');
}

/* ==========================
   GENERAR ZPL
========================== */
$zpl = "";

foreach ($stocks as $stock) {

    $codigo   = $stock['codigo_unico'];
    $producto = strtoupper($stock['nombre_producto']);

    $zpl .= "^XA
^JUS
^PW1240
^LL840
^LH0,0

^FO700,40
^A0R,50,50
^FD100 LBS.^FS

^FO700,1000
^A0R,50,50
^FD100 LBS.^FS

^FO600,420
^A0R,100,100
^FDYADIRA^FS

^FO300,250
^A0R,150,150
^FD{$producto}^FS

^BY3
^FO70,40
^BCR,120,Y,N,N
^FD{$codigo}^FS

^BY3
^FO70,640
^BCR,120,Y,N,N
^FD{$codigo}^FS

^XZ

";
}

/* ==========================
   LABELARY → PDF
========================== */
$ch = curl_init("https://api.labelary.com/v1/printers/8dpmm/labels/4x6/");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $zpl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/pdf"
]);

$pdf = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http !== 200) {
    die('Error al generar PDF en Labelary');
}

/* ==========================
   MOSTRAR PDF
========================== */
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="etiquetas_pacas_yadira.pdf"');
echo $pdf;
exit;
