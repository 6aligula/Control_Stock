<?php
// Inicio programa principal
// ********************************************************************************
session_start();
$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
require_once("modulos/datosBD.php");
require_once("modulos/html.php");
require_once("modulos/funciones.php");

//validarUsuario($url);
if (isset($_GET["id"])) {
    $url .= "?id={$_GET["id"]}";
}

if (isset($_POST["opcion"])) {
    $cuerpo = menuOpciones();
} else if (isset($_GET["id"])) {
    $cuerpo = mostrarRegistro("MOSTRAR");
} else {
    $cuerpo = mostrarFormulario();
}

//$menu = menu_cabecera_comun();
$menu = "";
$estiloContenedor = "container-fluid";

global $scriptsExtras;
$scriptsExtras = '<script type="text/javascript" src="js/unipolar.js"></script>';
mostrarHTML();
// ********************************************************************************
// Fin programa principal

/***********************************************************************************************************************
        iniciarCampos
        Descripcion
 ***********************************************************************************************************************/
function recibeDatosGet()
{
    //FILTROS
    $CURSOR["id_family"]             = isset($_GET["id_family"])         ? $_GET["id_family"] : "";
    $CURSOR["id_section"]                 = isset($_GET["id_section"])             ? $_GET["id_section"]     : "";
    $CURSOR["id_group"]                 = isset($_GET["id_group"])             ? $_GET["id_group"]     : "";
    return $CURSOR;
}


/***********************************************************************************************************************
        mostrarListado
        Descripcion
 ***********************************************************************************************************************/
//function mostrarListado($filtro = "") {
function mostrarFormulario()
{
    try {

        global $menu, $pagina, $titulo;
        $pagina = "unipolar";
        $titulo = "FORMULARIO CABLE UNIPOLAR";
        $texto = "";
        $CURSOR = recibeDatosGet();
        $id_family = $CURSOR['id_family'];
        $id_section = $CURSOR['id_section'];
        $id_group = $CURSOR['id_group'];
        //SELECT DEL MAXIMO CODIGO SEGUN LA COMBINACION DE id_section, id_family y id_group
        $ConsultaSQL = "SELECT MAX(codigo) as numero FROM devices WHERE id_section=" . $id_section . " AND id_family=" . $id_family . " AND id_group=" . $id_group;
        echo $ConsultaSQL;
        $codigo = consultaRegistroSQL($ConsultaSQL);
        $codigo['numero']++;

        if (isset($_SESSION["unipolar"])) unset($_SESSION["unipolar"]);

        $texto = <<<EOF
                <br>
                <center>
                    <input type="submit" name="opcion" value="MENU PRINCIPAL" class="btn-cancelar" />
                    <input type="submit" name="opcion" class="btn-cancelar" value="ATRAS" formnovalidate="formnovalidate" />
                </center>
                
                
                <!-- ---------------------------------------FILTROS--------------------------------------- -->
                
                <div style="width: 800px;">
                <h3 class="titulo" style="font-weight: bold;">Formulario para cables unipolar</h3>
                <h4>Campo comunes</h4>
                <br>
                <form method="post" action="#" enctype="multipart/form-data">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="img/cables/cables.jpg">
                        <div class="card-body">
                            <h5 class="card-title">Sube una foto</h5>
                            <div class="form-group">
                                <label for="image">Nueva Imagen:</label>
                                <input id="image" name="image" size="30" type="file">
                                <span id="rutaFoto"></span>
                            </div>
                            <input type="button" id="subirImagen" class="btn btn-primary upload" value="Subir">
                        </div>
                    </div>
                </form>
               
                </div>
                <br>
        
                <div>
                    <div class="caja-en-linea">
                        <label>Sección: </label>  
                        <p id="id_section"> $id_section </p>
                    </div>
                    <div class="caja-en-linea">
                        <label>Familia:</label>
                        <p id="id_family"> $id_family </p>
                    </div>
                    <div class="caja-en-linea">
                        <label>Grupo:</label>
                        <p id="id_group"> $id_group </p>
                    </div> 
                    <div class="caja-en-linea">
                        <label>Codigo de Producto:</label>
                        <p id="codigo"> {$codigo["numero"]} </p>
                    </div> 
                    <div>
                        <label>Codigo de barras</label>
                        <div id="codiBarras"> </div>
                    </div> 
                </div>
                <hr>
                    
                <div aria-label="First name">Campos obligatorios: *</div>
                <br>
        
                <div>
                    <div class="caja-en-linea">
                        <label>Fabricante*</label>
                        <input id="fabricante" name="fabricante" type="text" class="form-control" type="text" pattern="[a-zA-Z]{1,20}" title="solo letras" placeholder="Fabricante" aria-label="Last name" required>
                    </div>
                    <div class="caja-en-linea">
                        <label>Referencia de Fabricante*</label>
                        <input id="fabricanteRef" name="fabricanteRef" type="text" class="form-control" type="text" placeholder="Referencia de fabricante" aria-label="Last name" required>
                    </div>
                </div>

                <div class="clearfix">
                    <label>Observaciones</label>
                    <input id="observaciones" name="observaciones" type="text" class="form-control" type="text" placeholder="Observaciones" aria-label="Last name">
                </div>
        
                <br>
                <hr>

                <div class="clearfix">
                    <label>Campos personalizados:</label>
                </div>

                    <div class="clearfix">
                        <label for="acces" style="padding-right: 50px"> ¿Libre de Alogenos?</label>
                        <input type="checkbox" value="1" name="alogeno" class="alogeno">
                        <label for="si1" style="padding-right: 50px;"> Sí</label>
                        <input type="checkbox" value="0" name="alogeno" class="alogeno">
                        <label for="no1" style="padding-right: 50px;"> No</label>
                    </div>
                    <div class="clearfix">
                        <label for="acces" style="padding-right: 30px"> ¿Protección con malla?</label>
                        <input type="checkbox" value="1" name="malla" class="malla">
                        <label for="si1" style="padding-right: 50px;"> Sí</label>
                        <input type="checkbox" value="0" name="malla" class="malla">
                        <label for="no1" style="padding-right: 50px;"> No</label>
                    </div>
        
                    <br>
                    <div class="caja-en-linea">
                        <label>Cantidad (metros)*</label>
                        <input id="cantidad" name="cantidad" title="Introduce solo numeros" pattern="[0-9]{2}" type="text" class="form-control" placeholder="cantidad" aria-label="First name" required>
                    </div>
                    
                <div class="caja-en-linea">
                    <label>Color Aislante*</label>
                    <select id="colores">
                        <option value="blanco">Blanco</option>
                        <option value="negro">negro</option>
                        <option value="gris">gris</option>
                        <option value="rojo">rojo</option>
                        <option value="amarillo">amarillo</option>
                        <option value="azul">azul</option>
                        <option value="marron">marron</option>
                        <option value="verde-amarillo(tierra)">verde-amarillo(tierra)</option>
                    </select>
                </div>
                <br>
                <hr>
                <div class="clearfix">
                    <label>Datos del proveedor:</label>
                </div>
            
                <div class="caja-en-linea">
                    <label>Nombre de proveedor</label>
                    <select id="proveedores" class="form-control">
                        <option value="null"></option>
                        <option value="Guerin">Guerin</option>
                        <option value="Saltoki">Saltoki</option>
                    </select>
                </div>

                <div class="caja-en-linea">
                    <label>Referencia Proveedor*</label>
                    <input id="refProve" name="refProve" type="text" class="form-control" type="text" placeholder="Referencia Proveedor" aria-label="Last name" required>
                </div>

                <div class="caja-en-linea">
                    <label>Observaciones</label>
                    <input id="observaciones" name="observaciones" type="text" class="form-control" type="text" placeholder="Observaciones" aria-label="Last name">
                </div>

                <input type="submit" name="opcion" value="GUARDAR" class="btn-normal" />
            
                
                <!-- -------------------------------------FIN FILTROS------------------------------------- -->
                
                <p class="clearfix"></p>
                
                <center>
                    <input type="submit" name="opcion" value="MENU PRINCIPAL" class="btn-cancelar" />
                    <input type="submit" name="opcion" class="btn-cancelar" value="ATRAS" formnovalidate="formnovalidate" />
                </center>
                
                
                
EOF;
        return $texto;
    } catch (Exception $e) {
        return "Mostrar Listado: " . $e->getMessage();
    }
}

/***********************************************************************************************************************
        Listado_Secciones
        Descripcion
 ***********************************************************************************************************************/
function Listado_Secciones($listado, $valor, $filaCero)
{
    $lista = "";
    if (!empty($listado)) {
        $lista .= <<<EOF
                    <option value="0">{$filaCero}</option>
EOF;
        foreach ($listado as $fila) {
            $id = $fila["id"];
            $nombre = $fila["nombre_articulo"];
            $seleccionado = ($id == $valor) ? "selected" : "";
            $lista .= <<<EOF
                    <option value="{$id}" {$seleccionado}>{$nombre}</option>
EOF;
        }
    } else {
        $lista .= <<<EOF
                    <option value="0">"SIN ELEMENTOS..."</option>
EOF;
    }
    return <<<EOF
            <div class="list-group">
                $lista;
            </div>
EOF;
}

/***********************************************************************************************************************
        menuOpciones
        Descripcion
 ***********************************************************************************************************************/
function menuOpciones()
{
    $texto = "";

    switch ($_POST["opcion"]) {
        case "GUARDAR":
            $texto = addRegistro();
            break;
        case "ATRAS":
            header("location: seleccionArticulo.php");
            break;
        case "MENU PRINCIPAL":
            header("location: index.php");
            break;
        default:
            $texto = "NO HAY NINGUNA OPCIÓN SELECCIONADA.";
    }

    return $texto;
}
