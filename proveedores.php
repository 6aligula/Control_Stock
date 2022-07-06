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
        $CURSOR["f_name_supplier"]      = isset($_POST["f_name_supplier"])        ? $_POST["f_name_supplier"]        : "";
        $CURSOR["f_direction"]   = isset($_POST["f_direction"])     ? $_POST["f_direction"]     : "";
        //COMPROBACION
        $CURSOR["id"]            = isset($_GET["id"])               ? $_GET["id"]               : 0;
        //CAMPOS
        $CURSOR["name_supplier"]        = isset($_POST["name_supplier"])          ? $_POST["name_supplier"]          : "";
        $CURSOR["direction"]     = isset($_POST["direction"])       ? $_POST["direction"]       : "";
        $CURSOR["contact"]      = isset($_POST["contact"])        ? $_POST["contact"]        : "";
        $CURSOR["phone"]     = isset($_POST["phone"])       ? $_POST["phone"]       : "";
        $CURSOR["observations"] = isset($_POST["observations"])	? $_POST["observations"]	: "";
        $CURSOR["available"]    = isset($_POST["available"])      ? $_POST["available"]      : 0;
        
        return $CURSOR;
}


/***********************************************************************************************************************
        iniciarCampos
        Descripcion
***********************************************************************************************************************/    
function iniciarCampos() {
        //FILTROS
        $CURSOR["f_name_supplier"]      = "";
        $CURSOR["f_direction"]   = "";
        
        //CAMPOS
        $CURSOR["id"]            = "";
        $CURSOR["direction"]     = "";
        $CURSOR["contact"]      = "";
        $CURSOR["phone"]     = "";
        $CURSOR["observations"] = "";
        $CURSOR["available"]    = 0;

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
            $ConsultaSQL = "SELECT * FROM proveedores {$filtro} ORDER BY name_supplier ASC";
                                
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
                            <p class="negrita">name_supplier</p>
                            <input type="text" title="Introduzca el name_supplier o parte de el" id="f_name_supplier" name="f_name_supplier" placeholder="#name_supplier#" value="{$CURSOR["f_name_supplier"]}">
                        </div>
                        <div class="caja-en-linea">
                            <p class="negrita">direction</p>
                            <input type="text" title="Introduzca la direction o parte de ella" id="f_direction" name="f_direction" placeholder="#direction#" value="{$CURSOR["f_direction"]}">
                        </div>
                        
                        
                        <div class="btn-group ficha-detalle" role="group">
                            <button type="submit" id="opcion" name="opcion" class="btn-buscar" value="F_SUBMIT">
                            Buscar
                            </button>
                        </div>

                <!-- -------------------------------------FIN FILTROS------------------------------------- -->
                
                    <div class="centrado negrita">
                        LISTADO ORDENADO POR name_supplier DE PROVEEDOR, SELECCIONE UN PROVEEDOR PARA EDITARLO
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
                $name_supplier = $fila["name_supplier"];
                $direction = str_replace(PHP_EOL, '<br>', $fila["direction"]);
				$contact = $fila["contact"];
				$phone = $fila["phone"];
                $available = $fila["available"];
                
				$activado = "";
                if($available == 1) {
                    $activado = "<img src='img/activado.png' height='36px'>";
                }else{
                    $activado = "<img src='img/cancelado.png' height='36px'>";
                }

                $lista .= <<<EOF
                    <a href="?id={$id}" class="list-group-item resaltar_link">
                        <table class="ancho-total" >
                            <tr>
                                <td class="centrado" width="400">{$activado}</td>
                                <td class="izquierda truncate" width="300" title="{$name_supplier}">{$name_supplier}</td>
                                <td class="izquierda truncate" width="400">{$contact}</td>
                                <td class="izquierda truncate" width="400">{$phone}</td>
                                <td class="izquierda truncate" width="*">{$direction}</td>

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
                        <th class="centrado" width="400">available</th>
                        <th class="izquierda" width="300">name_supplier</th>
                        <th class="izquierda" width="400">CONTACT</th>
                        <th class="izquierda" width="400">phone</th>
                        <th class="izquierda" width="*">direction</th>

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
                            $noHayRegistro = "TAREA NO available";
                        }
                        
                        //COMPROBACION
                        $CURSOR["id"]                 = $registro["id"];  
                        //CAMPOS
                        $CURSOR["name_supplier"]             = $registro["name_supplier"];
                        $CURSOR["direction"]          = $registro["direction"];
                        $CURSOR["contact"]           = $registro["contact"];
                        $CURSOR["phone"]          = $registro["phone"];
                        $CURSOR["observations"]      = $registro["observations"];
                        $CURSOR["available"]         = $registro["available"];
                    }
                    
                    // INICIAMOS EL BOTON available
                    if($CURSOR["available"] == 1) {
                        $chk_available = "checked";
                        $imagen_available = '<img id="imagen_available" src="img/on.png" onClick="btn_available();" />';
                    } else {
                        $chk_available = "";
                        $imagen_available = '<img id="imagen_available" src="img/off.png" onClick="btn_available();" />';
                    }
                    
                    
                    if($noHayRegistro == "") {
                        $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="MODIFICAR" />                      
EOF;
                        $datos_complementarios = <<<EOF
                        
                        <div class="centrado negrita">
                            available<br>
                            <input type="checkbox" id="available" name="available" hidden {$chk_available} value="1"/> {$imagen_available}
                        
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
                    
                    // INICIAMOS EL BOTON available
                    //$chk_available = "";
                    //$imagen_available = '<img id="imagen_available" src="img/off.png" onClick="btn_available();" />';
                    $chk_available = "checked";
                    $imagen_available = '<img id="imagen_available" src="img/on.png" onClick="btn_available();" />';
                        
                    $botones = <<<EOF
                        
                        <input type="submit" name="opcion" class="btn-cancelar" value="VOLVER" formnovalidate="formnovalidate" />
                        <input type="submit" name="opcion" class="btn-normal" value="GUARDAR" />
EOF;
                        $datos_complementarios = <<<EOF
                        
                        <div class="centrado negrita">
                            available<br>
                            <input type="checkbox" id="available" name="available" hidden {$chk_available} value="1"/> {$imagen_available}
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
                        <label for="name_supplier" class="negrita">name_supplier DEL PROVEDOR <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="name_supplier del proveedor (Máx. 50 caracteres)"  id="name_supplier" name="name_supplier" placeholder="#name_supplier del proveedor (Máx. 50 caracteres)#" value="{$CURSOR["name_supplier"]}" autofocus />
                    
                    </p>
                    
					<p class="clearfix">
                        <label for="direction" class="negrita">direction DEL PROVEDOR <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="100" required class="form-control" title="direction del proveedor (Máx. 100 caracteres)"  id="direction" name="direction" placeholder="#direction del proveedor (Máx. 100 caracteres)#" value="{$CURSOR["direction"]}" />
                    
                    </p>
					
					<p class="clearfix">
                        <label for="contact" class="negrita">PERSONA DE CONTACT <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="Persona de contact (Máx. 50 caracteres)"  id="contact" name="contact" placeholder="#Persona de contact (Máx. 50 caracteres)#" value="{$CURSOR["contact"]}" />
                    
                    </p>
					
					 <p class="clearfix">
                        <label for="phone" class="negrita">phone <span class="anotacion"> (*)</span></label>
                    
                        <input type="text" maxlength="50" required class="form-control" title="phone (Máx. 50 caracteres)"  id="phone" name="phone" placeholder="#phone de contact (Máx. 50 caracteres)#" value="{$CURSOR["phone"]}" />
                    
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
                $name_supplier = $fila["name_supplier"];
                $seleccionado = ($id == $valor) ? "selected" : "";
                $lista .= <<<EOF
                    <option value="{$id}" {$seleccionado}>{$name_supplier}</option>
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
                $ConsultaSQL = "SELECT name_supplier FROM proveedores WHERE name_supplier = ? AND id <> ?;";
                $datos = array( $CURSOR["name_supplier"], $CURSOR["id"] );
                $existeRegistro = consultaRegistroExisteSQL($ConsultaSQL, $datos);
                
                if($existeRegistro == true) {
                    $texto .= "<p class='alerta'>Código duplicado</p>";
                    $texto .= mostrarRegistro("MOSTRAR");
                    $salir = true;
                } 
                
                if($salir == false) {
                            
                    $ConsultaSQL = "UPDATE proveedores SET 
                                                        name_supplier = ?,
                                                        direction = ?,
														contact = ?,
														phone = ?,
														observations = ?,
														available = ?
                                                    WHERE id = ?";

                    
                    $datos = array( mb_strtoupper($CURSOR["name_supplier"]),
                                    mb_strtoupper($CURSOR["direction"]), 
									mb_strtoupper($CURSOR["contact"]), 
									mb_strtoupper($CURSOR["phone"]), 
									mb_strtoupper($CURSOR["observations"]), 
                                    $CURSOR["available"],
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

            if($CURSOR["name_supplier"] != "" || $CURSOR["descripcion"] != "") {
                
                $ConsultaSQL = "INSERT INTO proveedores (id, 
                                                    name_supplier, 
                                                    direction, 
                                                    contact, 
                                                    phone, 
                                                    observations, 
                                                    available) 
                                                VALUES(?,?,?,?,?,?,?)";
                
                $datos = array( $CURSOR[0],
                                mb_strtoupper($CURSOR["name_supplier"]),
                                mb_strtoupper($CURSOR["direction"]),
                                mb_strtoupper($CURSOR["contact"]),
                                $CURSOR["phone"],
                                mb_strtoupper($CURSOR["observations"]),
                                $CURSOR["available"]);
                
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
                        $texto .= "<p class='alerta'>No ha sido posible añadir el registro. <br/>Compruebe que la referencia o el name_supplier del conjunto de proveedores no esté repetido.<p>";
                    }
                }               
            } else {
                $texto = mostrarRegistro("NUEVO");
                $texto .= "<p class='alerta'>Es necesario, por lo menos, una referencia y un name_supplier de conjunto de proveedores.<p>";
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
        if ($CURSOR["f_name_supplier"] != "" ){
            array_push($a_filtro, "name_supplier LIKE '%".$CURSOR["f_name_supplier"]."%'");
        }
        if ($CURSOR["f_direction"] != "" ){
            array_push($a_filtro, "direction LIKE '%".$CURSOR["f_direction"]."%'");
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
