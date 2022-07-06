<?php
    $pagina = "";
    $titulo = "";
    $menu = "";
    $cuerpo = "";
    $estiloContenedor = "";
    $formSubmit = "";
    $javaScripts = "";
    $scriptsExtras = "";

    function mostrarHTML() {
        try {
            global $menu, $pagina, $titulo, $estiloContenedor, 
                    $cuerpo, $formSubmit, $locationSubmit, 
                    $javaScripts, $scriptsExtras, $ruta_cabecera;
            if($ruta_cabecera == "../") {
                $ruta = $ruta_cabecera;
            } else {
                $ruta = "";
            }
            $version_title = "WAREHOUSE STOCK - v. 2022.04";
            $version_app = "ENTERPRISE NAME S.L. v. 22.04";
            $nom= "WAREHOUSE STOCK";
            
            $dia_sistema = date("d-m-Y");
            $hora_sistema = date("H:i:s");
            
            echo <<<EOF
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="{$ruta}res/favicon.ico">

        <title>{$version_title}</title>
        <link rel="stylesheet" href="{$ruta}jquery-ui-1.12.1/jquery-ui.min.css">
        <link rel="stylesheet" href="{$ruta}jquery-confirm-3.3.4/jquery-confirm.min.css">
        <link rel="stylesheet" href="{$ruta}jquery-editable-select-2.2.5/jquery-editable-select.min.css">
        <link rel="stylesheet" href="{$ruta}bootstrap-3.3.7/css/bootstrap.min.css">
        <!--link rel="stylesheet" href="{$ruta}bootstrap-select-1.13.9/css/bootstrap-select.min.css"-->
        
        <link rel="stylesheet" href="{$ruta}css/estilos.css">
        
    </head> 
    <body>
        <form id="formulario" name="formulario" method="post" action="{$formSubmit}">
        
            <!-- CABECERA -->
            <div class="cabecera">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-2 quitar-padding">
         
                        </div>

                        <div class="col-sm-8 quitar-padding centrado">
                            <span><img src="{$ruta}res/logo-inverso.png" class="imagen-logo"/></span> {$version_app}<br>
                            {$nom}
                        </div>

                        <div class="col-sm-2 quitar-padding derecha">
                            <div id="dia-sistema">{$dia_sistema}</div>
                            <div id="hora-sistema">{$hora_sistema}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIN CABECERA -->

            
            <!-- CUERPO -->
            <div class="relleno-cabecera"></div>
            <div class="{$estiloContenedor}">
                <div class="titulo-pagina">{$titulo}</div>
                {$cuerpo}
            </div> 
            <!--div class="relleno-pie"></div-->
            <!-- FIN CUERPO -->
                
        </form>
        
        <script src="{$ruta}jquery-3.4.1/jquery.min.js"></script>
        <script src="{$ruta}jquery-ui-1.12.1/jquery-ui.min.js"></scrip>
        <script src="{$ruta}jquery-confirm-3.3.4/jquery-confirm.min.js"></script>
        <script src="{$ruta}jquery-editable-select-2.2.5/jquery-editable-select.min.js"></script>
        <script src="{$ruta}bootstrap-3.3.7/js/bootstrap.min.js"></script>
        <!--script src="{$ruta}bootstrap-select-1.13.9/js/bootstrap-select.min.js"></script-->
        <script src="{$ruta}js/funciones.js"></script>
        <script language="JavaScript">
            function confirmar(mensaje){
                if(confirm(mensaje)) {
                    window.location.href = "{$locationSubmit}";
                }	
            }
            function enviarFormulario(url){
                document.getElementById("formulario").action = url;
                document.getElementById("formulario").submit();
            }
            {$javaScripts}
        </script> 
        {$scriptsExtras}
    </body>
</html>
EOF;

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }	

?>
