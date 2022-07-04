<?php

// Inicio programa principal
// ********************************************************************************
    session_start();

    $url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    require_once("modulos/funciones.php");
    require_once("modulos/html.php");
    
    $cuerpo = mostrarMenu();
    $menu = "";
    $titulo = "MENÚ PRINCIPAL";
    $estiloContenedor = "container";
    $scriptsExtras = '<script type="text/javascript" src="js/index.js"></script>';
    mostrarHTML();
// ********************************************************************************
// Fin programa principal


/***********************************************************************************************************************
	mostrarMenu
	Devuelve el cuerpo de la pagina index
/***********************************************************************************************************************/
    function mostrarMenu(){
          
        $texto = "<table>";
        for($x=0;$x<count(GLOBAL_COLOR_FONDO_FUENTE);$x+=4){
            $texto .= "<tr><td style='background:".GLOBAL_COLOR_FONDO_FUENTE[$x+0][0].";color:".GLOBAL_COLOR_FONDO_FUENTE[$x+0][1]."'>TEXTO   {$x}    </td>";
            $texto .=     "<td style='background:".GLOBAL_COLOR_FONDO_FUENTE[$x+1][0].";color:".GLOBAL_COLOR_FONDO_FUENTE[$x+1][1]."'>TEXTO ".($x+1)."</td>";
            $texto .=     "<td style='background:".GLOBAL_COLOR_FONDO_FUENTE[$x+2][0].";color:".GLOBAL_COLOR_FONDO_FUENTE[$x+2][1]."'>TEXTO ".($x+2)."</td>";
            $texto .=     "<td style='background:".GLOBAL_COLOR_FONDO_FUENTE[$x+3][0].";color:".GLOBAL_COLOR_FONDO_FUENTE[$x+3][1]."'>TEXTO ".($x+3)."</td></tr>";
        } 
        $texto .= "</table>";
        
        $texto = "";
        
        return <<<EOF
        {$texto}
        
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        
        <div class="centrado">
            <div class="row">
                <div class="col-sm-4">
                    <a href="proveedores.php" class="caja-en-linea btn-normal ancho-total sin-linea">
                        <img src="img/icono_productos.png" /><br>
						PROVEEDORES
                        <br>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="fabricantes.php" class="caja-en-linea btn-normal ancho-total sin-linea">
                        <img src="img/icono_hilo_roto.png" /><br>
                        FABRICANTES
                        <br>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="articulos.php" class="caja-en-linea btn-normal ancho-total sin-linea">
                        <img src="img/icono_trenzas.png" /><br>
                        ARTICULOS
                        <br>
                    </a>
                </div>
            </div>
        </div>
        
EOF;
    }
?>
