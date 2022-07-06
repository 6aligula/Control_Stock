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
    $cuerpo = mostrarListado();
}


//$cuerpo .= "<pre>POST:".print_r($_POST,true)."</pre>";
//$cuerpo .= "<pre>GET:".print_r($_GET,true)."</pre>";



//$menu = menu_cabecera_comun();
$menu = "";
$estiloContenedor = "container-fluid";

global $scriptsExtras;
$scriptsExtras = '<script type="text/javascript" src="js/devices.js"></script>';
mostrarHTML();
// ********************************************************************************
// Fin programa principal


/***********************************************************************************************************************
        cargarFormulario
        Recupera los datos en $CURSOR y llama a la funcion cuerpoFormulario pasandole $CURSOR como parametro
 ***********************************************************************************************************************/
function cargarFormulario()
{
    $CURSOR = recuperarDatosPOST();
    return cuerpoFormulario($CURSOR);
}



/***********************************************************************************************************************
        validarCampos
        Descripcion:
 ***********************************************************************************************************************/
function validarCampos()
{
    $new = true;
    $aviso = "";
    $CURSOR = recuperarDatosPOST();

    //COMPROBACION CORRECTA
    return cuerpoFormulario($CURSOR, $aviso, $new);
}



/***********************************************************************************************************************
        recuperarDatosPOST
        Descripcion:
 ***********************************************************************************************************************/
function recuperarDatosPOST()
{
    $CURSOR = iniciarCampos();
    //FILTROS
    $CURSOR["f_ref"]             = isset($_POST["f_ref"])         ? $_POST["f_ref"] : "";
    $CURSOR["f_name"]                 = isset($_POST["f_name"])             ? $_POST["f_name"]     : "";
    //COMPROBACION
    $CURSOR["id"]                       = isset($_GET["id"])                    ? $_GET["id"]               : 0;
    //CAMPOS
    $CURSOR["ref_manufacturer"]    = isset($_POST["ref_manufacturer"])? $_POST["ref_manufacturer"]   : "";
    $CURSOR["device_nom"]          = isset($_POST["device_nom"])      ? $_POST["device_nom"]         : "";
    $CURSOR["id_seccion"]               = isset($_POST["id_seccion"])           ? $_POST["id_seccion"]              : "";
    $CURSOR["id_familiy"]               = isset($_POST["id_familiy"])           ? $_POST["id_familiy"]              : "";
    $CURSOR["observations"]            = isset($_POST["observations"])        ? $_POST["observations"]           : "";
    return $CURSOR;
}


/***********************************************************************************************************************
        iniciarCampos
        Descripcion
 ***********************************************************************************************************************/
function iniciarCampos()
{
    //FILTROS
    $CURSOR["f_ref"]      = "";
    $CURSOR["f_name"]   = "";

    //CAMPOS
    $CURSOR["id"]                       = "";
    $CURSOR["ref_manufacturer"]    = "";
    $CURSOR["device_nom"]          = "";
    $CURSOR["id_seccion"]               = "";
    $CURSOR["id_familiy"]               = "";
    $CURSOR["observations"]            = "";
    return $CURSOR;
}


/***********************************************************************************************************************
        mostrarListado
        Descripcion
 ***********************************************************************************************************************/
//function mostrarListado($filtro = "") {
function mostrarListado()
{
    try {
        global $menu, $pagina, $titulo;
        $filtro = formarFiltro();
        $CURSOR = recuperarDatosPOST();

        $pagina = "devices";
        $titulo = "LIST OF DEVICES";
        $texto = "";

        if (isset($_SESSION["devices"])) unset($_SESSION["devices"]);

        //SELECT DE CONJUNTO DE devices PARA LISTAR
        $ConsultaSQL = "SELECT * FROM devices {$filtro} ORDER BY DEVICE_NOM ASC";

        $listado = consultaListadoSQL($ConsultaSQL);
        $listado_devices = "";
        $listado_devices = Listado_Sencillo($listado);


        $texto = <<<EOF
                <br>
				{$ConsultaSQL}
                <center>
                    <input type="submit" name="opcion" value="MENU PRINCIPAL" class="btn-cancelar" />
                    <input type="submit" name="opcion" value="AÑADIR ARTICULO" class="btn-normal" />
                </center>
                
                
                <!-- ---------------------------------------FILTROS--------------------------------------- -->
                
                <div class="centrado">
                    <h4 class="texto-destacado">FILTROS</h4>
                    <div class="borde-sombra">
                        <div class="caja-en-linea">
                            <p class="negrita">REFERENCIA</p>
                            <input type="text" title="Introduzca la referencia o parte de el" id="f_ref" name="f_ref" placeholder="#referencia#" value="{$CURSOR["f_ref"]}">
                        </div>
                        <div class="caja-en-linea">
                            <p class="negrita">NOMBRE</p>
                            <input type="text" title="Introduzca el nombre o parte de el" id="f_name" name="f_name" placeholder="#Nombre#" value="{$CURSOR["f_name"]}">
                        </div>
                        
                        
                        <div class="btn-group ficha-detalle" role="group">
                            <button type="submit" id="opcion" name="opcion" class="btn-buscar" value="F_SUBMIT">
                            Buscar
                            </button>
                        </div>

                <!-- -------------------------------------FIN FILTROS------------------------------------- -->
                
                    <div class="centrado negrita">
                        LISTADO ORDENADO POR NOMBRE, SELECCIONE UN ARTICULO PARA EDITARLO
                    </div>
                    
                    {$listado_devices}
                    <p class="clearfix"></p>
                </div>
                
                <p class="clearfix"></p>
                
                <center>
                    <input type="submit" name="opcion" value="MENU PRINCIPAL" class="btn-cancelar" />
                    <input type="submit" name="opcion" value="AÑADIR ARTICULO" class="btn-normal" />
                </center>
                
                
                
EOF;
        return $texto;
    } catch (Exception $e) {
        return "Mostrar Listado: " . $e->getMessage();
    }
}

/***********************************************************************************************************************
    Listado_sencillo
    Recibe una consulta y devuelve un listado con enlaces 
/***********************************************************************************************************************/
function Listado_Sencillo($listado)
{
    $lista = "";
    $num_registros = "Registros encontrados: 0";
    if (!empty($listado)) {
        $num_registros = "Registros encontrados: " . count($listado);
        foreach ($listado as $fila) {
            $id = $fila["id"];
            $referencia = $fila["ref_manufacturer"];
            $nombre = $fila["device_nom"];
            $id_seccion = $fila["id_seccion"];
            $id_familiy = $fila["id_familiy"];
            $observations = $fila["observations"];

            $lista .= <<<EOF
                    <a href="?id={$id}" class="list-group-item resaltar_link">
                        <table class="ancho-total" >
                            <tr>
                                <td class="centrado" width="300">{$referencia}</td>
                                <td class="izquierda truncate" width="300">{$nombre}</td>
                                <td class="izquierda truncate" width="400">{$id_seccion}</td>
                                <td class="izquierda truncate" width="400">{$id_familiy}</td>
                                <td class="izquierda truncate" width="*">{$observations}</td>
                            </tr>
                        </table>
                    </a>
EOF;
        }
    }
    return <<<EOF
            {$num_registros}
            <div class="list-group-item fondo-cyan-oscuro">
                <table class="ancho-total">
                    <tr class="">
                        <th class="centrado" width="300">REFERENCIA</th>               
                        <th class="izquierda" width="300">NOMBRE</th>
                        <th class="izquierda" width="400">ID SECCION</th>
                        <th class="izquierda" width="400">ID FAMILIA</th>
                        <th class="izquierda" width="*">observations</th>
                    </tr>
                </table>
            </div>
            {$lista}
            
EOF;
}

/***********************************************************************************************************************
        mostrarRegistro
        Descripcion
 ***********************************************************************************************************************/
function mostrarRegistro($tipoAccion)
{
    try {
        global $menu, $pagina, $titulo, $javaScripts;
        $pagina = "devices";
        $CURSOR = recuperarDatosPOST();
        
        $noHayRegistro = "";

        $botones = "";
        $datos_complementarios = "";

        switch ($tipoAccion) {
            case "MOSTRAR":

                $titulo = "MODIFICAR";
                $id = $_GET["id"];
/*                 $id_seccion = $_GET["id_seccion"];
                $id_familiy = $_GET["id_familiy"]; */

                if (empty($_POST)) {
                    $ConsultaSQL = "SELECT * FROM devices WHERE id = {$id}";
                    $registro = consultaRegistroSQL($ConsultaSQL);

                    if (empty($registro)) {
                        $noHayRegistro = "TAREA NO DISPONIBLE";
                    }

                    //COMPROBACION
                    $CURSOR["id"]                               = $registro["id"];
                    //CAMPOS           
                    $CURSOR["ref_manufacturer"]            = $registro["ref_manufacturer"];
                    $CURSOR["device_nom"]                  = $registro["device_nom"];
                    $CURSOR["id_seccion"]                       = $registro["id_seccion"];
                    $CURSOR["id_familiy"]                       = $registro["id_familiy"];
                    $CURSOR["observations"]                    = $registro["observations"];
                }


                if ($noHayRegistro == "") {
                    $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="MODIFICAR" />                      
EOF;
                } else {
                    $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER"  formnovalidate="formnovalidate" />                    
EOF;
                    $datos_complementarios = <<<EOF
                        
                        <div class="alerta">{$noHayRegistro}</div>                      
EOF;
                }
                break;
            case "new":
                $titulo = "AÑADIR";
                $id = 0;

                $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="SAVED" />
EOF;
                break;
        }

        if (isset($_SESSION["save"])) {
            if ($_SESSION["save"] == "new") {
                $registro_modificado = "<p class='mensaje'>Added record</p>";
            }
            if ($_SESSION["save"] == "modificado") {
                $registro_modificado = "<p class='mensaje'>modified record</p>";
            }

            unset($_SESSION["save"]);
        } else {
            $registro_modificado = "";
        }

        $resultado = <<<EOF
            
                    {$registro_modificado}
                    <center>{$botones}</center>
                    
					<p class="clearfix">
                        <label for="ref_manufacturer" class="negrita">REFERENCIA DEL FABRICANTE <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="100" required class="form-control" title="Referencia del fabricante (Máx. 100 caracteres)"  id="ref_manufacturer" name="ref_manufacturer" placeholder="#referencia del fabricante (Máx. 100 caracteres)#" value="{$CURSOR["ref_manufacturer"]}" />
                    
                    </p>
					
					<p class="clearfix">
                        <label for="device_nom" class="negrita">NOMBRE DEL ARTÍCULO <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Nombre del articulo (Máx. 50 caracteres)"  id="device_nom" name="device_nom" placeholder="#Nombre del articulo (Máx. 50 caracteres)#" value="{$CURSOR["device_nom"]}" />
                    
                    </p>
                    <p class="clearfix">
                        <label for="id_seccion" class="negrita">IDENTIFICADOR DE SECCIÓN <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Id de la sección (Máx. 50 caracteres)"  id="id_seccion" name="id_seccion" placeholder="#ID de la sección (Máx. 50 caracteres)#" value="{$CURSOR["id_seccion"]}" autofocus />
                    
                    </p>
                    <p class="clearfix">
                        <label for="id_familiy" class="negrita">IDENTIFICADOR DE FAMILIA <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Id de la familia (Máx. 50 caracteres)"  id="id_familiy" name="id_familiy" placeholder="#ID de la familia (Máx. 50 caracteres)#" value="{$CURSOR["id_familiy"]}" autofocus />
                    
                    </p>
					
					 <p class="clearfix">
                        <label for="observations" class="negrita">observations <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="observations (Máx. 50 caracteres)"  id="observations" name="observations" placeholder="#observations (Máx. 50 caracteres)#" value="{$CURSOR["observations"]}" />
                    
                    </p>
                    
                    <center>
                        <p span class="anotacion">(*) Campos obligatorios</p>
                        {$datos_complementarios}
                        <br>
                        {$botones}
                    </center>                   
EOF;


        return $resultado;
    } catch (Exception $e) {
        return "Mostrar Conjunto de fabricantes: " . $e->getMessage();
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
            $nombre = $fila["device_nom"];
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
        SAVEDRegistro
        Descripcion
 ***********************************************************************************************************************/
function SAVEDRegistro()
{
    try {
        $CURSOR = recuperarDatosPOST();

        $texto = "";
        //$continuar = ($_SESSION["proveedores"] != $CURSOR) ? true : false;
        $continuar = True;
        // Si se ha producido algún cambio, intentamos modificar el registro
        $salir = false;
        if ($continuar) {
            $ConsultaSQL = "SELECT device_nom FROM devices WHERE device_nom = ? AND id <> ?;";
            $datos = array($CURSOR["device_nom"], $CURSOR["id"]);
            $existeRegistro = consultaRegistroExisteSQL($ConsultaSQL, $datos);

            if ($existeRegistro == true) {
                $texto .= "<p class='alerta'>Código duplicado</p>";
                $texto .= mostrarRegistro("MOSTRAR");
                $salir = true;
            }

            if ($salir == false) {

                $ConsultaSQL = "UPDATE devices SET 
                                                        ref_manufacturer = ?,
                                                        device_nom = ?,
														id_seccion = ?,
														id_familiy = ?,
														observations = ?
                                                    WHERE id = ?";


                $datos = array(
                    mb_strtoupper($CURSOR["ref_manufacturer"]),
                    mb_strtoupper($CURSOR["device_nom"]),
                    mb_strtoupper($CURSOR["id_seccion"]),
                    mb_strtoupper($CURSOR["id_familiy"]),
                    mb_strtoupper($CURSOR["observations"]),
                    $CURSOR["id"]
                );

                $resultado = sentenciaSQL($ConsultaSQL, $datos);

                if ($resultado === false) {
                    $texto .= "<p class='alerta'>No ha sido posible la modificación del registro en estos momentos, inténtelo más tarde</p>";
                } else {
                    if ($resultado == 1) {
                        //unset($_SESSION["proveedores"]);
                        //header("location:proveedores.php");

                        $texto .= "<p class='mensaje'>REGISTRO MODIFICADO</p>";
                        $texto .= mostrarRegistro("MOSTRAR");
                    } else {
                        //$texto .= $resultado;
                        //$texto .= mostrarListado();
                        $texto .= "<p class='alerta'>No ha sido posible realizar la modificación del registro<br>Seguramente no se han realizado cambios en los datos</p>";
                        $texto .= mostrarRegistro("MOSTRAR");
                    }   // fin if($resultado==1) 

                }       // fin if($resultado===false) 

            }           // fin if ($existeRegistro == true) 

        } else {
            // No se han producido cambios, asi que salimos
            //unset($_SESSION["proveedores"]);
            //header("location:proveedores.php");
            $texto .= "<p class='alerta'>No se ha producido cambios</p>";
            $texto .= mostrarRegistro("MOSTRAR");
        }
        return $texto;
    } catch (Exception $e) {
        return "SAVED Conjunto de proveedores : " . $e->getMessage();
    }
}


/***********************************************************************************************************************
        addRegistro
        Descripcion
 ***********************************************************************************************************************/
function addRegistro()
{
    try {
        $CURSOR = recuperarDatosPOST();

        $texto  = "";

        if ($CURSOR["device_nom"] != "" || $CURSOR["ref_manufacturer"] != "") {

            $ConsultaSQL = "INSERT INTO devices (id, 
                                                    ref_manufacturer, 
                                                    device_nom, 
                                                    id_seccion, 
                                                    id_familiy), 
                                                    observations) 
                                                VALUES(?,?,?,?,?,?)";

            $datos = array(
                $CURSOR[0],
                mb_strtoupper($CURSOR["ref_manufacturer"]),
                mb_strtoupper($CURSOR["device_nom"]),
                mb_strtoupper($CURSOR["id_seccion"]),
                mb_strtoupper($CURSOR["id_familiy"]),
                mb_strtoupper($CURSOR["observations"])
            );

            $resultado = sentenciaSQL($ConsultaSQL, $datos);

            if ($resultado === false) {
                $texto .= "No ha sido posible la añadir el registro en estos momentos, inténtelo más tarde.";
            } else {
                if ($resultado == 1) {
                    $ConsultaSQL = "SELECT MAX(id) AS id_new FROM devices;";
                    $registro = consultaRegistroSQL($ConsultaSQL);

                    $id = $registro["id_new"];
                    $_SESSION["save"] = "new";
                    header("location:devices.php?id={$id}");
                    //header("location:proveedores.php");
                } else {
                    $texto = $resultado . "<br>";
                    $texto .= mostrarRegistro("new");
                    $texto .= "<p class='alerta'>No ha sido posible añadir el registro. <br/>Compruebe que la referencia o el nombre del conjunto de devices no esté repetido.<p>";
                }
            }
        } else {
            $texto = mostrarRegistro("new");
            $texto .= "<p class='alerta'>Es necesario, por lo menos, una referencia y un nombre de conjunto de devices.<p>";
        }
        return $texto;
    } catch (Exception $e) {
        return "AÑADIR TAREA: " . $e->getMessage();
    }
}

/***********************************************************************************************************************
        formarFiltro
        Descripcion
 ***********************************************************************************************************************/
function formarFiltro()
{
    $CURSOR = recuperarDatosPOST();
    //SI SE ENVIA ALGUN FILTRO AÑADE WHERE
    $texto = "";
    $a_filtro = array();
    //COMPROBACION ENVIADO
    if ($CURSOR["f_ref"] != "") {
        array_push($a_filtro, "ref_manufacturer LIKE '%" . $CURSOR["f_ref"] . "%'");
    }
    if ($CURSOR["f_name"] != "") {
        array_push($a_filtro, "device_nom LIKE '%" . $CURSOR["f_name"] . "%'");
    }

    if (count($a_filtro) != 0) {
        $texto .= " WHERE " . implode(" AND ", $a_filtro);
    }
    return $texto;
}



/***********************************************************************************************************************
        menuOpciones
        Descripcion
 ***********************************************************************************************************************/
function menuOpciones()
{
    $texto = "";

    switch ($_POST["opcion"]) {
        case "F_SUBMIT":
            //$filtro = formarFiltro();
            //$texto = mostrarListado($filtro);
            $texto = mostrarListado();
            break;
        case "AÑADIR ARTICULO":
            header("location: seleccionArticulo.php");
            break;
        case "MODIFICAR":
            $texto = SAVEDRegistro();
            break;
        case "SAVED":
            $texto = addRegistro();
            break;
        case "VOLVER":
            header("location: devices.php");
            break;
        case "MENU PRINCIPAL":
            header("location: index.php");
            break;
        default:
            $texto = "NO HAY NINGUNA OPCIÓN SELECCIONADA.";
    }

    return $texto;
}