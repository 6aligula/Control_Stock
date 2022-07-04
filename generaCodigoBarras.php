<?php
$comanda_post = $_POST['comanda_post'];
$elementos_comanda = explode(';', $comanda_post);
$id_seccion = $elementos_comanda[0];
$id_familia = $elementos_comanda[1];
$id_grupo = $elementos_comanda[2];
$codigo = $elementos_comanda[3];
/*pasar a int los valores para que funciona str_pad */
$id_seccion = intval($id_seccion);
$id_familia = intval($id_familia);
$codigo = intval($codigo);
$id_grupo = intval($id_grupo);


$barcodeText = insicium($id_seccion).'-'.insicium($id_familia).'-'.insicium($id_grupo).'-'.insicium($codigo);

//defino el tipo de codigo de barras code 128
$barcodeType = 'Code128';
//defino la orientacion del codigo de barras
$barcodeDisplay = 'horizontal';
//defino la altura del codigo de barras
$barcodeSize = '20';
//mostrar el texto debajo del codigo de barras
$printText = 'true';

if ($barcodeText != '') {
    echo '<img alt="' . $barcodeSize . '"  src="php-barcode-master/barcode.php?text=' . $barcodeText . '&codetype=' . $barcodeType . '&orientation=' . $barcodeDisplay . '&size=' . $barcodeSize . '&print=' . $printText . '"/>';
} else {
    echo '<div class="alert alert-danger">Introduzca el nombre del producto para generar el código</div>';
}


//añade ceros a un codigo
function insicium($valor){
    $long=intval(3);
    $caracterRelleno='0';
    return str_pad($valor, $long, $caracterRelleno, STR_PAD_LEFT);
    
}