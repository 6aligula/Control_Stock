<?php
// Recibo los datos de la imagen
$nombre_img = $_FILES['image']['name'];
$tipo = $_FILES['image']['type'];
$tamano = $_FILES['image']['size'];
$temp = $_FILES['image']['tmp_name'];
$directorioLocal = 'img/';
//recibo los datos para el nombre de la imagen
$id_section = $_POST['id_section'];
$id_familiy = $_POST['id_familiy'];
$id_group = $_POST['id_group'];
$code = $_POST['code'];

//renombrar imagen
$extension = pathinfo($directorioLocal . $nombre_img, PATHINFO_EXTENSION);
//$base = pathinfo($temp . $nombre_img, PATHINFO_BASENAME);
//echo $base;
$nuevo_nombre_img = trim($id_section) . trim($id_familiy) . trim($id_group) . trim($code) . "." . $extension;

//Si existe imagen y tiene un tamaño correcto
if (!empty($nombre_img) && ($_FILES['image']['size'] <= 200000000)) {
   //indicamos los formatos que permitimos subir a nuestro servidor
   if ((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png"))) {

      // Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
      move_uploaded_file($temp, $directorioLocal . $nuevo_nombre_img);
      //llama a la funciona para redimensionar la imagen
      redimensionaFinal($directorioLocal, $nuevo_nombre_img, $extension);
      //devuelvo la ruta de la imagen, esto e slo que guardare en la base de gatos
      echo $directorioLocal . $nuevo_nombre_img;
   } else {
      //si no cumple con el formato
      echo "No se puede subir una imagen con ese formato ";
   }
} else {
   //si existe la variable pero se pasa del tamaño permitido
   if ($nombre_img == !NULL) echo "La imagen es demasiado grande ";
}

// Funcion principal para la gestion del tipo a imagen a redimensionar
function redimensionaFinal($directorioLocal, $nuevo_nombre_img, $extension)
{
   //redimensionar imagen
   //tipo de imagen
   switch ($extension) {
      case "jpeg":
         //Crear variable de imagen a partir de la original
         $original = imagecreatefromjpeg($directorioLocal . $nuevo_nombre_img);
         redimensiona($directorioLocal, $nuevo_nombre_img, $original, $extension);
         break;
      case "jpg":
         $original = imagecreatefromjpeg($directorioLocal . $nuevo_nombre_img);
         redimensiona($directorioLocal, $nuevo_nombre_img, $original, $extension);
         break;
      case "png":
         $original = imagecreatefrompng($directorioLocal . $nuevo_nombre_img);
         redimensiona($directorioLocal, $nuevo_nombre_img, $original, $extension);
         break;
      case "gif":
         $original = imagecreatefromgif($directorioLocal . $nuevo_nombre_img);
         redimensiona($directorioLocal, $nuevo_nombre_img, $original, $extension);
         break;
      default:
         $texto = "NO HAY NINGUNA OPCIÓN SELECCIONADA.";
   }
}
//funcion para redimensionar las imagenes
function redimensiona($directorioLocal, $nuevo_nombre_img, $original, $extension)
{
   //Definir tamaño máximo y mínimo
   $max_ancho = 960;
   $max_alto = 512;

   //Recoger ancho y alto de la original
   list($ancho, $alto) = getimagesize($directorioLocal . $nuevo_nombre_img);

   //Calcular proporción ancho y alto
   $x_ratio = $max_ancho / $ancho;
   $y_ratio = $max_alto / $alto;

   if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {
      //Si es más pequeña que el máximo no redimensionamos
      $ancho_final = $ancho;
      $alto_final = $alto;
   }
   //si no calculamos si es más alta o más ancha y redimensionamos
   elseif (($x_ratio * $alto) < $max_alto) {
      $alto_final = ceil($x_ratio * $alto);
      $ancho_final = $max_ancho;
   } else {
      $ancho_final = ceil($y_ratio * $ancho);
      $alto_final = $max_alto;
   }

   //Crear lienzo en blanco con proporciones
   $lienzo = imagecreatetruecolor($ancho_final, $alto_final);

   //Copiar $original sobre la imagen que acabamos de crear en blanco ($tmp)
   imagecopyresampled($lienzo, $original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);

   //Limpiar memoria
   imagedestroy($original);

   //Definimos la calidad de la imagen final
   $cal = 90;
   //creo la imagen segun el tipo
   creaImagen($lienzo, $directorioLocal, $nuevo_nombre_img, $cal, $extension);
}
//crear la imagen en la ruta segun tipo de imagen, jpg, png , etc
function creaImagen($lienzo, $directorioLocal, $nuevo_nombre_img, $cal, $extension)
{
   //tipo de imagen
   switch ($extension) {
      case "jpeg":
         //Se crea la imagen final en el directorio indicado
         imagejpeg($lienzo, $directorioLocal . $nuevo_nombre_img, $cal);
         break;
      case "jpg":
         //Se crea la imagen final en el directorio indicado
         imagejpeg($lienzo, $directorioLocal . $nuevo_nombre_img, $cal);
         break;
      case "png":
         //Se crea la imagen final en el directorio indicado
         imagepng($lienzo, $directorioLocal . $nuevo_nombre_img, 9);
         //echo $extension;
         break;
      case "gif":
         //Se crea la imagen final en el directorio indicado
         imagegif($lienzo, $directorioLocal . $nuevo_nombre_img, $cal);
         break;

      default:
         $texto = "NO HAY NINGUNA OPCIÓN SELECCIONADA.";
   }
}
