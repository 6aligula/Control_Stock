<?php 
// Inicio programa principal
// ********************************************************************************
    session_start();
    $url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    require_once("modulos/datosBD.php");
    require_once("modulos/html.php");
    require_once("modulos/funciones.php");

    //validarUsuario($url);
    if (isset($_GET["id"]) ) {
        $url .= "?id={$_GET["id"]}";
    }
    
    if (isset($_POST["opcion"]) ) {
        $cuerpo = menuOpciones();
    } else if (isset($_GET["id"]) ) {
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
    $scriptsExtras = '<script type="text/javascript" src="js/proveedores.js"></script>';
    mostrarHTML();
// ********************************************************************************
// Fin programa principal


/***********************************************************************************************************************
        cargarFormulario
        Recupera los datos en $CURSOR y llama a la funcion cuerpoFormulario pasandole $CURSOR como parametro
***********************************************************************************************************************/
function cargarFormulario() {
    $CURSOR = recuperarDatosPOST();
    return cuerpoFormulario($CURSOR);
}



/***********************************************************************************************************************
        validarCampos
        Descripcion:
***********************************************************************************************************************/
function validarCampos() {
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
function recuperarDatosPOST() {
        $CURSOR = iniciarCampos();
        //FILTROS
        $CURSOR["f_nombre"]      = isset($_POST["f_nombre"])        ? $_POST["f_nombre"]        : "";
        $CURSOR["f_direccion"]   = isset($_POST["f_direccion"])     ? $_POST["f_direccion"]     : "";
        //COMPROBACION
        $CURSOR["id"]            = isset($_GET["id"])               ? $_GET["id"]               : 0;
        //CAMPOS
        $CURSOR["nombre"]        = isset($_POST["nombre"])          ? $_POST["nombre"]          : "";
        $CURSOR["direccion"]     = isset($_POST["direccion"])       ? $_POST["direccion"]       : "";
        $CURSOR["contacto"]      = isset($_POST["contacto"])        ? $_POST["contacto"]        : "";
        $CURSOR["telefonos"]     = isset($_POST["telefonos"])       ? $_POST["telefonos"]       : "";
        $CURSOR["observaciones"] = isset($_POST["observaciones"])	? $_POST["observaciones"]	: "";
        $CURSOR["disponible"]    = isset($_POST["disponible"])      ? $_POST["disponible"]      : 0;
        
        return $CURSOR;
}


/***********************************************************************************************************************
        iniciarCampos
        Descripcion
***********************************************************************************************************************/    
function iniciarCampos() {
        //FILTROS
        $CURSOR["f_nombre"]      = "";
        $CURSOR["f_direccion"]   = "";
        
        //CAMPOS
        $CURSOR["id"]            = "";
        $CURSOR["direccion"]     = "";
        $CURSOR["contacto"]      = "";
        $CURSOR["telefonos"]     = "";
        $CURSOR["observaciones"] = "";
        $CURSOR["disponible"]    = 0;

        return $CURSOR;
}


/***********************************************************************************************************************
        mostrarListado
        Descripcion
***********************************************************************************************************************/    
    //function mostrarListado($filtro = "") {
    function mostrarListado() {
        try {
            global $menu, $pagina, $titulo;
            $filtro = formarFiltro();
            $CURSOR = recuperarDatosPOST();
            
            $pagina = "proveedores";
            $titulo = "LISTADO DE PROVEEDORES";
            $texto = "";
            
            if(isset($_SESSION["proveedores"])) unset($_SESSION["proveedores"]);
            
            //SELECT DE CONJUNTO DE proveedores PARA LISTAR
            $ConsultaSQL = "SELECT * FROM proveedores {$filtro} ORDER BY nombre ASC";
                                
            $listado = consultaListadoSQL($ConsultaSQL);
            $listado_proveedores = "";
            $listado_proveedores = Listado_Sencillo($listado);
            
           
            $texto = <<<EOF
                <br>
				{$ConsultaSQL}
                <center>
                    <input type="submit" name="opcion" value="MENU PRINCIPAL" class="btn-cancelar" />
                    <input type="submit" name="opcion" value="AÑADIR PROVEEDOR" class="btn-normal" />
                </center>
                
                
                <!-- ---------------------------------------FILTROS--------------------------------------- -->
                
                <div class="centrado">
                    <h4 class="texto-destacado">FILTROS</h4>
                    <div class="borde-sombra">
                        <div class="caja-en-linea">
                            <p class="negrita">NOMBRE</p>
                            <input type="text" title="Introduzca el nombre o parte de el" id="f_nombre" name="f_nombre" placeholder="#Nombre#" value="{$CURSOR["f_nombre"]}">
                        </div>
                        <div class="caja-en-linea">
                            <p class="negrita">DIRECCION</p>
                            <input type="text" title="Introduzca la direccion o parte de ella" id="f_direccion" name="f_direccion" placeholder="#Direccion#" value="{$CURSOR["f_direccion"]}">
                        </div>
                        
                        
                        <div class="btn-group ficha-detalle" role="group">
                            <button type="submit" id="opcion" name="opcion" class="btn-buscar" value="F_SUBMIT">
                            Buscar
                            </button>
                        </div>

                <!-- -------------------------------------FIN FILTROS------------------------------------- -->
                
                    <div class="centrado negrita">
                        LISTADO ORDENADO POR NOMBRE DE PROVEEDOR, SELECCIONE UN PROVEEDOR PARA EDITARLO
                    </div>
                    
                    {$listado_proveedores}
                    <p class="clearfix"></p>
                </div>
                
                <p class="clearfix"></p>
                
                <center>
                    <input type="submit" name="opcion" value="MENU PRINCIPAL" class="btn-cancelar" />
                    <input type="submit" name="opcion" value="AÑADIR PROVEEDOR" class="btn-normal" />
                </center>
                
                
                
EOF;
            return $texto;
        }catch(Exception $e){
            return "Mostrar Listado: ".$e->getMessage();
        }
    }
    
/***********************************************************************************************************************
    Listado_sencillo
    Recibe una consulta y devuelve un listado con enlaces 
/***********************************************************************************************************************/
    function Listado_Sencillo($listado) {
        $lista = "";
        $num_registros="Registros encontrados: 0";
        if(!empty($listado)) {
            $num_registros="Registros encontrados: ".count($listado);
            foreach($listado as $fila){
                $id = $fila["id"];
                $nombre = $fila["nombre"];
                $direccion = str_replace(PHP_EOL, '<br>', $fila["direccion"]);
				$contacto = $fila["contacto"];
				$telefonos = $fila["telefonos"];
                $disponible = $fila["disponible"];
                
				$activado = "";
                if($disponible == 1) {
                    $activado = "<img src='img/activado.png' height='36px'>";
                }else{
                    $activado = "<img src='img/cancelado.png' height='36px'>";
                }

                $lista .= <<<EOF
                    <a href="?id={$id}" class="list-group-item resaltar_link">
                        <table class="ancho-total" >
                            <tr>
                                <td class="centrado" width="400">{$activado}</td>
                                <td class="izquierda truncate" width="300" title="{$nombre}">{$nombre}</td>
                                <td class="izquierda truncate" width="400">{$contacto}</td>
                                <td class="izquierda truncate" width="400">{$telefonos}</td>
                                <td class="izquierda truncate" width="*">{$direccion}</td>

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
                        <th class="centrado" width="400">DISPONIBLE</th>
                        <th class="izquierda" width="300">NOMBRE</th>
                        <th class="izquierda" width="400">CONTACTO</th>
                        <th class="izquierda" width="400">TELEFONOS</th>
                        <th class="izquierda" width="*">DIRECCION</th>

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
    function mostrarRegistro($tipoAccion) {
        try {
            global $menu, $pagina, $titulo, $javaScripts;
            $pagina = "proveedores";
            $CURSOR = recuperarDatosPOST();

            $noHayRegistro = "";
            
            $botones = "";
            $datos_complementarios = "";
            
            switch($tipoAccion) {
                case "MOSTRAR":
                    
                    $titulo = "MODIFICAR";
                    $id = $_GET["id"];  
                    
                    if(empty($_POST)) {
                        $ConsultaSQL = "SELECT * FROM proveedores WHERE id = {$id}";
                        $registro = consultaRegistroSQL($ConsultaSQL);
                        
                        if(empty($registro)) {
                            $noHayRegistro = "TAREA NO DISPONIBLE";
                        }
                        
                        //COMPROBACION
                        $CURSOR["id"]                 = $registro["id"];  
                        //CAMPOS
                        $CURSOR["nombre"]             = $registro["nombre"];
                        $CURSOR["direccion"]          = $registro["direccion"];
                        $CURSOR["contacto"]           = $registro["contacto"];
                        $CURSOR["telefonos"]          = $registro["telefonos"];
                        $CURSOR["observaciones"]      = $registro["observaciones"];
                        $CURSOR["disponible"]         = $registro["disponible"];
                    }
                    
                    // INICIAMOS EL BOTON DISPONIBLE
                    if($CURSOR["disponible"] == 1) {
                        $chk_disponible = "checked";
                        $imagen_disponible = '<img id="imagen_disponible" src="img/on.png" onClick="btn_Disponible();" />';
                    } else {
                        $chk_disponible = "";
                        $imagen_disponible = '<img id="imagen_disponible" src="img/off.png" onClick="btn_Disponible();" />';
                    }
                    
                    
                    if($noHayRegistro == "") {
                        $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="MODIFICAR" />                      
EOF;
                        $datos_complementarios = <<<EOF
                        
                        <div class="centrado negrita">
                            DISPONIBLE<br>
                            <input type="checkbox" id="disponible" name="disponible" hidden {$chk_disponible} value="1"/> {$imagen_disponible}
                        
                        </div>              
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
                    
                    // INICIAMOS EL BOTON DISPONIBLE
                    //$chk_disponible = "";
                    //$imagen_disponible = '<img id="imagen_disponible" src="img/off.png" onClick="btn_Disponible();" />';
                    $chk_disponible = "checked";
                    $imagen_disponible = '<img id="imagen_disponible" src="img/on.png" onClick="btn_Disponible();" />';
                        
                    $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="GUARDAR" />
EOF;
                        $datos_complementarios = <<<EOF
                        
                        <div class="centrado negrita">
                            DISPONIBLE<br>
                            <input type="checkbox" id="disponible" name="disponible" hidden {$chk_disponible} value="1"/> {$imagen_disponible}
                        </div>                  
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
                        <label for="nombre" class="negrita">NOMBRE DEL PROVEDOR <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Nombre del proveedor (Máx. 50 caracteres)"  id="nombre" name="nombre" placeholder="#Nombre del proveedor (Máx. 50 caracteres)#" value="{$CURSOR["nombre"]}" autofocus />
                    
                    </p>
                    
					<p class="clearfix">
                        <label for="direccion" class="negrita">DIRECCION DEL PROVEDOR <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="100" required class="form-control" title="Direccion del proveedor (Máx. 100 caracteres)"  id="direccion" name="direccion" placeholder="#Direccion del proveedor (Máx. 100 caracteres)#" value="{$CURSOR["direccion"]}" />
                    
                    </p>
					
					<p class="clearfix">
                        <label for="contacto" class="negrita">PERSONA DE CONTACTO <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Persona de contacto (Máx. 50 caracteres)"  id="contacto" name="contacto" placeholder="#Persona de contacto (Máx. 50 caracteres)#" value="{$CURSOR["contacto"]}" />
                    
                    </p>
					
					 <p class="clearfix">
                        <label for="telefonos" class="negrita">TELEFONOS <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Telefonos (Máx. 50 caracteres)"  id="telefonos" name="telefonos" placeholder="#Telefonos de contacto (Máx. 50 caracteres)#" value="{$CURSOR["telefonos"]}" />
                    
                    </p>
                    
                    <center>
                        <p span class="anotacion">(*) Campos obligatorios</p>
                        {$datos_complementarios}
                        <br>
                        {$botones}
                    </center>                   
EOF;
            
            
            return $resultado;
        }catch(Exception $e){
            return "Mostrar Conjunto de proveedores: ".$e->getMessage();
        }
    }


/***********************************************************************************************************************
        Listado_Secciones
        Descripcion
***********************************************************************************************************************/    
    function Listado_Secciones($listado, $valor, $filaCero) {
        $lista = "";
        if(!empty($listado)) {
            $lista .= <<<EOF
                    <option value="0">{$filaCero}</option>
EOF;
            foreach($listado as $fila){
                $id = $fila["id"];
                $nombre = $fila["nombre"];
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
    function guardarRegistro() {
        try{
            $CURSOR = recuperarDatosPOST();
            
            $texto = "";
            //$continuar = ($_SESSION["proveedores"] != $CURSOR) ? true : false;
            $continuar = True;
            // Si se ha producido algún cambio, intentamos modificar el registro
            $salir = false;
            if($continuar) {
                $ConsultaSQL = "SELECT nombre FROM proveedores WHERE nombre = ? AND id <> ?;";
                $datos = array( $CURSOR["nombre"], $CURSOR["id"] );
                $existeRegistro = consultaRegistroExisteSQL($ConsultaSQL, $datos);
                
                if($existeRegistro == true) {
                    $texto .= "<p class='alerta'>Código duplicado</p>";
                    $texto .= mostrarRegistro("MOSTRAR");
                    $salir = true;
                } 
                
                if($salir == false) {
                            
                    $ConsultaSQL = "UPDATE proveedores SET 
                                                        nombre = ?,
                                                        direccion = ?,
														contacto = ?,
														telefonos = ?,
														observaciones = ?,
														disponible = ?
                                                    WHERE id = ?";

                    
                    $datos = array( mb_strtoupper($CURSOR["nombre"]),
                                    mb_strtoupper($CURSOR["direccion"]), 
									mb_strtoupper($CURSOR["contacto"]), 
									mb_strtoupper($CURSOR["telefonos"]), 
									mb_strtoupper($CURSOR["observaciones"]), 
                                    $CURSOR["disponible"],
                                    $CURSOR["id"]);
                    
                    $resultado = sentenciaSQL($ConsultaSQL, $datos);

                    if($resultado===false) {
                        $texto .= "<p class='alerta'>No ha sido posible la modificación del registro en estos momentos, inténtelo más tarde</p>";
                    } else {
                        if($resultado==1) {
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
        }catch(Exception $e){
            return "Guardar Conjunto de proveedores : ".$e->getMessage();
        }
    }


/***********************************************************************************************************************
        addRegistro
        Descripcion
***********************************************************************************************************************/        
    function addRegistro() {
        try {
            $CURSOR = recuperarDatosPOST();
            
            $texto  = "";

            if($CURSOR["nombre"] != "" || $CURSOR["descripcion"] != "") {
                
                $ConsultaSQL = "INSERT INTO proveedores (id, 
                                                    nombre, 
                                                    direccion, 
                                                    contacto, 
                                                    telefonos, 
                                                    observaciones, 
                                                    disponible) 
                                                VALUES(?,?,?,?,?,?,?)";
                
                $datos = array( $CURSOR[0],
                                mb_strtoupper($CURSOR["nombre"]),
                                mb_strtoupper($CURSOR["direccion"]),
                                mb_strtoupper($CURSOR["contacto"]),
                                $CURSOR["telefonos"],
                                mb_strtoupper($CURSOR["observaciones"]),
                                $CURSOR["disponible"]);
                
                $resultado = sentenciaSQL($ConsultaSQL, $datos);
                
                if($resultado===false) {
                    $texto .= "No ha sido posible la añadir el registro en estos momentos, inténtelo más tarde.";
                } else {
                    if($resultado==1) {
                        $ConsultaSQL = "SELECT MAX(id) AS id_nuevo FROM proveedores;";
                        $registro = consultaRegistroSQL($ConsultaSQL);
                        
                        $id = $registro["id_nuevo"];
                        $_SESSION["guardardo"] = "nuevo";
                        header("location:proveedores.php?id={$id}");
                        //header("location:proveedores.php");
                    }else{
                        $texto = $resultado."<br>";
                        $texto .= mostrarRegistro("NUEVO");
                        $texto .= "<p class='alerta'>No ha sido posible añadir el registro. <br/>Compruebe que la referencia o el nombre del conjunto de proveedores no esté repetido.<p>";
                    }
                }               
            } else {
                $texto = mostrarRegistro("NUEVO");
                $texto .= "<p class='alerta'>Es necesario, por lo menos, una referencia y un nombre de conjunto de proveedores.<p>";
            }
            return $texto;
        }catch(Exception $e){
            return "AÑADIR TAREA: ".$e->getMessage();
        }
    }

/***********************************************************************************************************************
        formarFiltro
        Descripcion
***********************************************************************************************************************/    
    function formarFiltro(){
        $CURSOR = recuperarDatosPOST();
        //SI SE ENVIA ALGUN FILTRO AÑADE WHERE
        $texto = "";
        $a_filtro= array();
        //COMPROBACION ENVIADO
        if ($CURSOR["f_nombre"] != "" ){
            array_push($a_filtro, "nombre LIKE '%".$CURSOR["f_nombre"]."%'");
        }
        if ($CURSOR["f_direccion"] != "" ){
            array_push($a_filtro, "direccion LIKE '%".$CURSOR["f_direccion"]."%'");
        }
  
        if(count($a_filtro) != 0) {
        $texto .= " WHERE ".implode(" AND ",$a_filtro);
        }
        return $texto;  
    }

    
    
/***********************************************************************************************************************
        menuOpciones
        Descripcion
***********************************************************************************************************************/    
    function menuOpciones() {
        $texto = "";

        switch ($_POST["opcion"]) {
            case "F_SUBMIT":
                //$filtro = formarFiltro();
                //$texto = mostrarListado($filtro);
                $texto = mostrarListado();
                break;
            case "AÑADIR PROVEEDOR":
                $texto = mostrarRegistro("NUEVO");
                break;
            case "MODIFICAR":
                $texto = guardarRegistro();
                break;
            case "GUARDAR":
                $texto = addRegistro();
                break;
            case "VOLVER":
                header("location: proveedores.php"); 
                break;
            case "MENU PRINCIPAL":
                header("location: index.php"); 
                break;
            default:
               $texto = "NO HAY NINGUNA OPCIÓN SELECCIONADA.";
        }   
	
        return $texto;      
    }
