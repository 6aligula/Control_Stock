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
$scriptsExtras = '<script type="text/javascript" src="js/articulos.js"></script>';
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
    $nuevo = true;
    $aviso = "";
    $CURSOR = recuperarDatosPOST();

    //COMPROBACION CORRECTA
    return cuerpoFormulario($CURSOR, $aviso, $nuevo);
}



/***********************************************************************************************************************
        recuperarDatosPOST
        Descripcion:
 ***********************************************************************************************************************/
function recuperarDatosPOST()
{
    $CURSOR = iniciarCampos();
    //FILTROS
    $CURSOR["f_referencia"]             = isset($_POST["f_referencia"])         ? $_POST["f_referencia"] : "";
    $CURSOR["f_nombre"]                 = isset($_POST["f_nombre"])             ? $_POST["f_nombre"]     : "";
    //COMPROBACION
    $CURSOR["id"]                       = isset($_GET["id"])                    ? $_GET["id"]               : 0;
    //CAMPOS
    $CURSOR["referencia_fabricante"]    = isset($_POST["referencia_fabricante"])? $_POST["referencia_fabricante"]   : "";
    $CURSOR["nombre_articulo"]          = isset($_POST["nombre_articulo"])      ? $_POST["nombre_articulo"]         : "";
    $CURSOR["id_seccion"]               = isset($_POST["id_seccion"])           ? $_POST["id_seccion"]              : "";
    $CURSOR["id_familia"]               = isset($_POST["id_familia"])           ? $_POST["id_familia"]              : "";
    $CURSOR["observaciones"]            = isset($_POST["observaciones"])        ? $_POST["observaciones"]           : "";
    return $CURSOR;
}


/***********************************************************************************************************************
        iniciarCampos
        Descripcion
 ***********************************************************************************************************************/
function iniciarCampos()
{
    //FILTROS
    $CURSOR["f_referencia"]      = "";
    $CURSOR["f_nombre"]   = "";

    //CAMPOS
    $CURSOR["id"]                       = "";
    $CURSOR["referencia_fabricante"]    = "";
    $CURSOR["nombre_articulo"]          = "";
    $CURSOR["id_seccion"]               = "";
    $CURSOR["id_familia"]               = "";
    $CURSOR["observaciones"]            = "";
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

        $pagina = "articulos";
        $titulo = "LISTADO DE ARTICULOS";
        $texto = "";

        if (isset($_SESSION["articulos"])) unset($_SESSION["articulos"]);

        //SELECT DE CONJUNTO DE articulos PARA LISTAR
        $ConsultaSQL = "SELECT * FROM articulos {$filtro} ORDER BY nombre_articulo ASC";

        $listado = consultaListadoSQL($ConsultaSQL);
        $listado_articulos = "";
        $listado_articulos = Listado_Sencillo($listado);


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
                            <input type="text" title="Introduzca la referencia o parte de el" id="f_referencia" name="f_referencia" placeholder="#referencia#" value="{$CURSOR["f_referencia"]}">
                        </div>
                        <div class="caja-en-linea">
                            <p class="negrita">NOMBRE</p>
                            <input type="text" title="Introduzca el nombre o parte de el" id="f_nombre" name="f_nombre" placeholder="#Nombre#" value="{$CURSOR["f_nombre"]}">
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
                    
                    {$listado_articulos}
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
            $referencia = $fila["referencia_fabricante"];
            $nombre = $fila["nombre_articulo"];
            $id_seccion = $fila["id_seccion"];
            $id_familia = $fila["id_familia"];
            $observaciones = $fila["observaciones"];

            $lista .= <<<EOF
                    <a href="?id={$id}" class="list-group-item resaltar_link">
                        <table class="ancho-total" >
                            <tr>
                                <td class="centrado" width="300">{$referencia}</td>
                                <td class="izquierda truncate" width="300">{$nombre}</td>
                                <td class="izquierda truncate" width="400">{$id_seccion}</td>
                                <td class="izquierda truncate" width="400">{$id_familia}</td>
                                <td class="izquierda truncate" width="*">{$observaciones}</td>
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
                        <th class="izquierda" width="*">OBSERVACIONES</th>
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
        $pagina = "articulos";
        $CURSOR = recuperarDatosPOST();
        
        $noHayRegistro = "";

        $botones = "";
        $datos_complementarios = "";

        switch ($tipoAccion) {
            case "MOSTRAR":

                $titulo = "MODIFICAR";
                $id = $_GET["id"];
/*                 $id_seccion = $_GET["id_seccion"];
                $id_familia = $_GET["id_familia"]; */

                if (empty($_POST)) {
                    $ConsultaSQL = "SELECT * FROM articulos WHERE id = {$id}";
                    $registro = consultaRegistroSQL($ConsultaSQL);

                    if (empty($registro)) {
                        $noHayRegistro = "TAREA NO DISPONIBLE";
                    }

                    //COMPROBACION
                    $CURSOR["id"]                               = $registro["id"];
                    //CAMPOS           
                    $CURSOR["referencia_fabricante"]            = $registro["referencia_fabricante"];
                    $CURSOR["nombre_articulo"]                  = $registro["nombre_articulo"];
                    $CURSOR["id_seccion"]                       = $registro["id_seccion"];
                    $CURSOR["id_familia"]                       = $registro["id_familia"];
                    $CURSOR["observaciones"]                    = $registro["observaciones"];
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
            case "NUEVO":
                $titulo = "AÑADIR";
                $id = 0;

                $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="GUARDAR" />
EOF;
                break;
        }

        if (isset($_SESSION["guardardo"])) {
            if ($_SESSION["guardardo"] == "nuevo") {
                $registro_modificado = "<p class='mensaje'>REGISTRO AÑADIDO</p>";
            }
            if ($_SESSION["guardardo"] == "modificado") {
                $registro_modificado = "<p class='mensaje'>REGISTRO MODIFICADO</p>";
            }

            unset($_SESSION["guardardo"]);
        } else {
            $registro_modificado = "";
        }

        $resultado = <<<EOF
            
                    {$registro_modificado}
                    <center>{$botones}</center>
                    
					<p class="clearfix">
                        <label for="referencia_fabricante" class="negrita">REFERENCIA DEL FABRICANTE <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="100" required class="form-control" title="Referencia del fabricante (Máx. 100 caracteres)"  id="referencia_fabricante" name="referencia_fabricante" placeholder="#referencia del fabricante (Máx. 100 caracteres)#" value="{$CURSOR["referencia_fabricante"]}" />
                    
                    </p>
					
					<p class="clearfix">
                        <label for="nombre_articulo" class="negrita">NOMBRE DEL ARTÍCULO <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Nombre del articulo (Máx. 50 caracteres)"  id="nombre_articulo" name="nombre_articulo" placeholder="#Nombre del articulo (Máx. 50 caracteres)#" value="{$CURSOR["nombre_articulo"]}" />
                    
                    </p>
                    <p class="clearfix">
                        <label for="id_seccion" class="negrita">IDENTIFICADOR DE SECCIÓN <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Id de la sección (Máx. 50 caracteres)"  id="id_seccion" name="id_seccion" placeholder="#ID de la sección (Máx. 50 caracteres)#" value="{$CURSOR["id_seccion"]}" autofocus />
                    
                    </p>
                    <p class="clearfix">
                        <label for="id_familia" class="negrita">IDENTIFICADOR DE FAMILIA <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Id de la familia (Máx. 50 caracteres)"  id="id_familia" name="id_familia" placeholder="#ID de la familia (Máx. 50 caracteres)#" value="{$CURSOR["id_familia"]}" autofocus />
                    
                    </p>
					
					 <p class="clearfix">
                        <label for="observaciones" class="negrita">OBSERVACIONES <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Observaciones (Máx. 50 caracteres)"  id="observaciones" name="observaciones" placeholder="#Observaciones (Máx. 50 caracteres)#" value="{$CURSOR["observaciones"]}" />
                    
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
        guardarRegistro
        Descripcion
 ***********************************************************************************************************************/
function guardarRegistro()
{
    try {
        $CURSOR = recuperarDatosPOST();

        $texto = "";
        //$continuar = ($_SESSION["proveedores"] != $CURSOR) ? true : false;
        $continuar = True;
        // Si se ha producido algún cambio, intentamos modificar el registro
        $salir = false;
        if ($continuar) {
            $ConsultaSQL = "SELECT nombre_articulo FROM articulos WHERE nombre_articulo = ? AND id <> ?;";
            $datos = array($CURSOR["nombre_articulo"], $CURSOR["id"]);
            $existeRegistro = consultaRegistroExisteSQL($ConsultaSQL, $datos);

            if ($existeRegistro == true) {
                $texto .= "<p class='alerta'>Código duplicado</p>";
                $texto .= mostrarRegistro("MOSTRAR");
                $salir = true;
            }

            if ($salir == false) {

                $ConsultaSQL = "UPDATE articulos SET 
                                                        referencia_fabricante = ?,
                                                        nombre_articulo = ?,
														id_seccion = ?,
														id_familia = ?,
														observaciones = ?
                                                    WHERE id = ?";


                $datos = array(
                    mb_strtoupper($CURSOR["referencia_fabricante"]),
                    mb_strtoupper($CURSOR["nombre_articulo"]),
                    mb_strtoupper($CURSOR["id_seccion"]),
                    mb_strtoupper($CURSOR["id_familia"]),
                    mb_strtoupper($CURSOR["observaciones"]),
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
        return "Guardar Conjunto de proveedores : " . $e->getMessage();
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

        if ($CURSOR["nombre_articulo"] != "" || $CURSOR["referencia_fabricante"] != "") {

            $ConsultaSQL = "INSERT INTO articulos (id, 
                                                    referencia_fabricante, 
                                                    nombre_articulo, 
                                                    id_seccion, 
                                                    id_familia), 
                                                    observaciones) 
                                                VALUES(?,?,?,?,?,?)";

            $datos = array(
                $CURSOR[0],
                mb_strtoupper($CURSOR["referencia_fabricante"]),
                mb_strtoupper($CURSOR["nombre_articulo"]),
                mb_strtoupper($CURSOR["id_seccion"]),
                mb_strtoupper($CURSOR["id_familia"]),
                mb_strtoupper($CURSOR["observaciones"])
            );

            $resultado = sentenciaSQL($ConsultaSQL, $datos);

            if ($resultado === false) {
                $texto .= "No ha sido posible la añadir el registro en estos momentos, inténtelo más tarde.";
            } else {
                if ($resultado == 1) {
                    $ConsultaSQL = "SELECT MAX(id) AS id_nuevo FROM articulos;";
                    $registro = consultaRegistroSQL($ConsultaSQL);

                    $id = $registro["id_nuevo"];
                    $_SESSION["guardardo"] = "nuevo";
                    header("location:articulos.php?id={$id}");
                    //header("location:proveedores.php");
                } else {
                    $texto = $resultado . "<br>";
                    $texto .= mostrarRegistro("NUEVO");
                    $texto .= "<p class='alerta'>No ha sido posible añadir el registro. <br/>Compruebe que la referencia o el nombre del conjunto de articulos no esté repetido.<p>";
                }
            }
        } else {
            $texto = mostrarRegistro("NUEVO");
            $texto .= "<p class='alerta'>Es necesario, por lo menos, una referencia y un nombre de conjunto de articulos.<p>";
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
    if ($CURSOR["f_referencia"] != "") {
        array_push($a_filtro, "referencia_fabricante LIKE '%" . $CURSOR["f_referencia"] . "%'");
    }
    if ($CURSOR["f_nombre"] != "") {
        array_push($a_filtro, "nombre_articulo LIKE '%" . $CURSOR["f_nombre"] . "%'");
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
            $texto = guardarRegistro();
            break;
        case "GUARDAR":
            $texto = addRegistro();
            break;
        case "VOLVER":
            header("location: articulos.php");
            break;
        case "MENU PRINCIPAL":
            header("location: index.php");
            break;
        default:
            $texto = "NO HAY NINGUNA OPCIÓN SELECCIONADA.";
    }

    return $texto;
}