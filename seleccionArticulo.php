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

//$menu = menu_cabecera_comun();
$menu = "";
$estiloContenedor = "container-fluid";

global $scriptsExtras;
$scriptsExtras = '<script type="text/javascript" src="js/seleccionArticulo.js"></script>';
mostrarHTML();
// ********************************************************************************
// Fin programa principal


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
    $CURSOR["referencia_fabricante"]    = isset($_POST["referencia_fabricante"]) ? $_POST["referencia_fabricante"]   : "";
    $CURSOR["nombre_articulo"]          = isset($_POST["nombre_articulo"])      ? $_POST["nombre_articulo"]         : "";
    $CURSOR["id_seccion"]               = isset($_POST["id_seccion"])           ? $_POST["id_seccion"]              : "";
    $CURSOR["id_familia"]               = isset($_POST["id_familia"])           ? $_POST["id_familia"]              : "";
    $CURSOR["id_grupo"]               = isset($_POST["id_grupo"])           ? $_POST["id_grupo"]              : "";
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
    $CURSOR["id_grupo"]               = "";
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

        $pagina = "seleccion";
        $titulo = "SELECCIÓN DE ARTICULOS";
        $texto = "";

        if (isset($_SESSION["seleccion"])) unset($_SESSION["seleccion"]);

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
                    <input type="submit" name="opcion" class="btn-cancelar" value="ATRAS" formnovalidate="formnovalidate" />
                </center>
                
                
                <!-- ---------------------------------------FILTROS--------------------------------------- -->
                
                    <h1>Seleccion de formulario de entrada</h1>
                    <br>
                        <div class="caja-en-linea">
                            <label for="cdc">Sección</label><br>
                            <td>
                                <select id="id_seccion" name="id_seccion">
                                    <option value="null"></option>
                                    <option value="1" id="Familia_Automatismos" name="1">A Automatismos</option>              
                                    <option value="2" id="Familia_Fungibles" name="2">F Fungibles</option>              
                                </select>
                            </td>
                        </div>
                        <div class="caja-en-linea">
                            <label for="cdc">Familia</label><br>
                            <td>
                                <select id="id_familia" name="id_familia">
                                    <option value="null"></option>
                                    <optgroup label="Familia_Automatismos" id="id_automatismos">
                                        <option value="null"></option>
                                        <option >Armarios y Cajas</option>
                                        <option >Bornas y Conectores</option>
                                        <option >Botonera y Balizas</option>
                                        <option >Contactores y Disyuntores</option>
                                        <option >Fotocelulas, finales de carrera, encoder y detectores</option>
                                        <option value="3" name="1" id= "Fuentes_de_alimentacion_transformadores_y_fusibles">Fuentes de alimentación transformadores y fusibles</option>
                                        <option value="5" name="1" id= "Magentotermicos_y_diferenciales">Magnetotérmicos y diferenciales</option>
                                        <option >Motores, servos y control</option>
                                        <option >PLC, pantallas y visualizadores</option>
                                        <option >Relés y Temporizadores</option>
                                        <option >Seccionadore, Bases y Distribución</option>
                                        <option >Relés y Temporizadores</option>
                                    </optgroup>

                                    <optgroup label="Familia_Fungibles" id="id_fungibles">
                                        <option value="null"></option>
                                        <option class="2" value="4" name="2" id="Mangueras_y_cables">F_Mangueras y cables</option>
                                        <option class="2">F_Canales y guias</option>
                                        <option class="2">F_Terminales, numeración y señalización</option>
                                        <option class="2">F_Varios</option>
                                    </optgroup>
                                </select>
                            </td>
                        </div>
                        <div class="caja-en-linea">
                            <label for="cdc">GRUPO</label><br>
                            <td>
                                <select id="id_grupo" name="id_grupo">                                        
                                    <optgroup label="Fuentes_de_alimentacion_transformadores_y_fusibles">
                                        <option value="null"></option>
                                        <option value="4">Fuentes de Alimentación</option>
                                        <option value="5">Transformadores</option>
                                        <option value="6">Fusibles</option>                                      
                                    </optgroup>

                                    <optgroup label="Magentotermicos_y_diferenciales">
                                        <option value="null"></option>
                                        <option value="7">Magnetotérmico</option>
                                        <option value="8">Diferencial</option>
                                    </optgroup>

                                    <optgroup label="Mangueras_y_cables" id="AR1">
                                        <option value="null"></option>
                                        <option value="1" name="1">Unipolar</option>
                                        <option value="2" name="2">Manguera</option>
                                        <option value="3" name="3">Confeccionado</option>
                                    </optgroup>
      
                
                                </select>
                            </td>
                        </div>
                        <input type="submit" name="opcion" value="AÑADIR ARTICULO" class="btn-normal" />
                
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
                    <input type="submit" name="opcion" class="btn-cancelar" value="ATRAS" formnovalidate="formnovalidate" />
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
                                <td class="izquierda truncate" width="200">{$id_seccion}</td>
                                <td class="izquierda truncate" width="200">{$id_familia}</td>
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
                        <th class="izquierda" width="200">ID SECCION</th>
                        <th class="izquierda" width="200">ID FAMILIA</th>
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
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="ATRAS" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="MODIFICAR" />                      
EOF;
                } else {
                    $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="ATRAS"  formnovalidate="formnovalidate" />                    
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
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="ATRAS" formnovalidate="formnovalidate" />
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
        añadir un articulo
        voy contra el formulario elegido por el usuario con id_seccion, id_familia e id_grupo
 ***********************************************************************************************************************/
function afegirArticle()
{
    try {
        $CURSOR = recuperarDatosPOST();

        $id_seccion = $CURSOR["id_seccion"];
        $id_familia = $CURSOR["id_familia"];
        $id_grupo = $CURSOR["id_grupo"];
        //header('location: seleccionArticulo.php?id_grupo='.$id_grupo);
        /* echo $id_familia;
        echo $id_grupo; */
        $rutaFormulario = elegirFormulario($id_grupo);
        //header('location: seleccionArticulo.php?'.$rutaFormulario);
        header("location: " . $rutaFormulario . $id_seccion . "&id_familia=" . $id_familia . "&id_grupo=" . $id_grupo);
    } catch (Exception $e) {
        return "Error al enviar id_seccion, id_familia o id_grupo " . $e->getMessage();
    }
}

function elegirFormulario($id_grupo)
{
    $texto = "";
    switch ($id_grupo) {
        case "1":
            $texto = "unipolar.php?id_seccion=";
            break;
        case "2":
            $texto = "manguera.php?id_seccion=";
            break;
        case "3":
            $texto = "confeccionado.php?id_seccion=";
            break;
        case "4":
            break;
        default:
            $texto = "NO HAY NINGUNA OPCIÓN SELECCIONADA.";
    }
    return $texto;
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
            $texto = afegirArticle();
            break;
        case "MODIFICAR":
            $texto = guardarRegistro();
            break;
        case "GUARDAR":
            $texto = addRegistro();
            break;
        case "ATRAS":
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