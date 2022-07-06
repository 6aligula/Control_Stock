<?php
    // CONSTANTES GLOBALES  
    //define("DB_HOST"            , "127.0.0.1");
    define("DB_HOST"            , "localhost");
    define("DB_PORT"	        , "3306");
    define("DB_USER"	        , "user");
    define("DB_PASSWORD"        , "password");
    define("DB_DB"		        , "nameDB");
    

    // String connection para base de datos MySQL
    define("DB_PDO_MySQL_STRING_CONNECTION", "mysql:host=".DB_HOST.":".DB_PORT.";dbname=".DB_DB.";charset=utf8"); 
    // String que utilizaremos para conectarse a los datos
    define("DB_PDO_CONNECTION", DB_PDO_MySQL_STRING_CONNECTION);

    define("TIEMPO_SESION", 1800);
    define("DB_PASO_PAGINA", 3);
    define("DB_MAX_REGISTROS_PAGINA", 20);

    //echo "<p>".DB_PDO_MySQL_STRING_CONNECTION."</p>";

    function conectarBD(){
        try{
            $cn = new PDO(DB_PDO_CONNECTION, DB_USER, DB_PASSWORD);
            $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            $cn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $cn;
        }catch(Exception $e){
            echo "ConectarBD: ".$e->getMessage();
            return null;
        }
    }
    
    // *******************************************************************************************
    // Ejecuta una sentencia SQL recibida como parametro y devuelve un listado en forma de array.
    // *******************************************************************************************
    function consultaListadoSQL($ConsultaSQL){
        try {
            $cn = conectarBD();	
            $resultado = $cn->query($ConsultaSQL);
            if($resultado===false) {
                echo 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
            }
            $resultado->setFetchMode(PDO::FETCH_ASSOC);
            $listado = $resultado->fetchAll();	
            // liberar memoria recurso
            $resultado->closeCursor();
            // cerrar conexión
            $cn = null;
            return $listado;
        }catch(Exception $e){
            echo "consultaListadoSQL: ".$e->getMessage();
            return null;
        }
    }
    
    // *******************************************************************************************
    // Ejecuta una sentencia SQL recibida como parametro y devuelve un registro en forma de array.
    // *******************************************************************************************
    function consultaRegistroSQL($ConsultaSQL){ 
        try {
            $resultado = false;
            $registro = false;
            $cn = conectarBD();	
            $resultado = $cn->query($ConsultaSQL);
            if($resultado===false) {
                echo 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
            } else {
                $registro = $resultado->fetch();
                // liberar memoria recurso
                $resultado->closeCursor();
            }
            // cerrar conexión
            $cn = null;
            return $registro;
        }catch(Exception $e){
            echo "consultaRegistroSQL: ".$e->getMessage();
            return null;
        }
    }
    
    // **************************************************************************************************************
    // Ejecuta una sentencia SQL de tipo DELETE/UPDATE/INSERT que recibida como parametros la sentencia y los campos
    //  y devuelve el numero de registros afectados por la modificación.
    // **************************************************************************************************************
    function sentenciaSQL($ConsultaSQL, $campos){
        try {
    //			echo "<p>".$ConsultaSQL."</p>";
    //			echo "<pre>".print_r($campos)."</pre>";
            $resultado = 0;
            $cn = conectarBD();	
            $Tabla = $cn->prepare($ConsultaSQL);
            $Tabla->execute($campos);
            if($Tabla===false) {
                echo 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
                $resultado = false;
            } else {
                $resultado = $Tabla->rowCount();
            }			
            // cerrar conexión
            $cn = null;
            return $resultado;
        }catch(Exception $e){
            //echo "<p>modificar Registro: ".$e->getMessage()."</p>";
            //return 0;
            return "<p>SENTENCIA SQL: ".$e->getMessage()."</p>";
        }
    }
    
    // *******************************************************************************************
    // Ejecuta una sentencia SQL recibida como parametro y devuelve si el registro existe o no.
    // *******************************************************************************************
    function consultaRegistroExisteSQL($ConsultaSQL, $campos){
        try {
    //			echo "<p>".$ConsultaSQL."</p>";
    //			echo "<pre>".print_r($campos)."</pre>";
            $resultado = false;
            $cn = conectarBD();	
            $Tabla = $cn->prepare($ConsultaSQL);
            $Tabla->execute($campos);
            if($Tabla===false) {
                echo 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
                $resultado = false;
            } else {
                if ($Tabla->rowCount() > 0) {
                    $resultado = true;
                }
            }			
            // cerrar conexión
            $cn = null;
        }catch(Exception $e){
            //echo "<p>modificar Registro: ".$e->getMessage()."</p>";
            //return 0;
        }
        return $resultado;
    }

    // **************************************************************************************************************
    // Ejecuta una sentencia SQL sin pasar parametos.
    // **************************************************************************************************************	
    function ModificarSQL($ConsultaSQL){
        try {
            //echo $ConsultaSQL;
            $cn = conectarBD();	
            
            $Tabla = $cn->prepare($ConsultaSQL);
            
            $Tabla->execute();
            
            //echo "\nPDO::errorCode(): ", $cn->errorCode();
            
            // cerrar conexión
            $cn = null;
            
            if($Tabla===false) {
                return 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
            } else {
                return true;
            }		
            
        }catch(Exception $e){
            //return "Modificar SQL: ".$e->getMessage();
            return 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
        }
    }

    // **************************************************************************************************************
    // Ejecuta una sentencia SQL sin pasar parametos.
    // **************************************************************************************************************	
    function ModificarSQL_Con_Parametros($ConsultaSQL, $campos){
        try {
            //echo $ConsultaSQL;
            $cn = conectarBD();	
            
            $Tabla = $cn->prepare($ConsultaSQL);
            $Tabla->execute($campos);
            
            //echo "\nPDO::errorCode(): ", $cn->errorCode();
            
            // cerrar conexión
            $cn = null;
            
            if($Tabla===false) {
                return 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
            } else {
                return true;
            }		
            
        }catch(Exception $e){
            //return "Modificar SQL: ".$e->getMessage();
            return 'Error: '.$cn->errorInfo()[0].". ".$cn->errorInfo()[2];
        }
    }

    function paginar_Listado($url, $num_Registros = 0, $filtro = "") {
        try {
            $pagina = 0;
            $reg_inicio = 0;
            $paginacion = array(
                "inicio"=>0,
                "paginas"=>""
            );
            
            //examino la página a mostrar y el inicio del registro a mostrar
            if(isset($_GET["id"])) {
                $id = "?id={$_GET["id"]}&";
            } else {
                $id = "?";
            }
            if(isset($_GET["pagina"])) {
                $pagina = $_GET["pagina"];
                $paginacion["inicio"] = ($pagina - 1) * DB_MAX_REGISTROS_PAGINA;
                $reg_inicio = $paginacion["inicio"]+1;
            } else {
                if($num_Registros > 0){
                    $pagina = 1;
                    $reg_inicio = 1;
                }
            }
            
            //calculo el total de páginas
            $total_paginas = ceil($num_Registros / DB_MAX_REGISTROS_PAGINA); 

            //pongo el número de registros total y la página que se muestra
            $num_registros = "<p>".$reg_inicio." de ".$num_Registros." registros encontrados - Página ".$pagina." de ".$total_paginas."<p>"; 
            
            $paginacion["paginas"] = $num_registros;
            
            $paginas[1] = array("valor"=>1, "texto"=>"<<", "titulo"=>"IR A LA PRIMERA PÁGINA");
            $paginas[2] = array("valor"=>2, "texto"=>"<", "titulo"=>"IR A LA PÁGINA ANTERIOR");
            $paginas[3] = array("valor"=>3, "texto"=>"3", "titulo"=>"");
            $paginas[4] = array("valor"=>4, "texto"=>">", "titulo"=>"IR A LA PÁGINA SIGUIENTE");
            $paginas[5] = array("valor"=>$total_paginas, "texto"=>">>", "titulo"=>"IR A LA ÚLTIMA PÁGINA");
            
            switch($total_paginas){
                case 0:
                case 1:
                    break;
                case 2:
                case 3:
                case 4:
                case 5:
                    for ($i=1;$i<=$total_paginas;$i++){
                        if($pagina == $i){
                            //si muestro el índice de la página actual, no coloco enlace
                            $paginacion["paginas"] .= "<span>{$pagina}</span> ";
                        } else {
                            $filtro_base64 = base64_encode($filtro);
                            //si el índice no corresponde con la página mostrada actualmente, coloco el enlace para ir a esa página
                            $paginacion["paginas"] .= <<<EOF
                                <a href='{$url}{$id}pagina={$i}&filtro={$filtro_base64}'
                                    title='{$paginas[$i]["titulo"]}'>{$i}</a>
EOF;
                        }
                    }
                    break;
                default:
                    if($pagina == 1) {
                        $paginas[1]["texto"] = 1;
                        $paginas[2]["texto"] = 2;
                        $paginas[2]["titulo"] = "IR A LA PÁGINA 2";
                        $paginas[4]["valor"] = $pagina +1;
                    }
                    if($pagina == 2) {
                        $paginas[2]["valor"] = 2;
                        $paginas[2]["texto"] = 2;
                        $paginas[2]["titulo"] = "IR A LA PÁGINA 2";
                        $paginas[4]["valor"] = $pagina +1;
                    }
                    if($pagina > 3) {
                        $paginas[2]["valor"] = $pagina -1;
                        $paginas[3]["valor"] = $pagina;
                        $paginas[3]["texto"] = $pagina;
                        $paginas[4]["valor"] = $pagina +1;
                    }
                    
                    if($pagina == $total_paginas-1) {
                        $paginas[2]["valor"] = $pagina -1;
                        $paginas[3]["valor"] = $pagina -1;
                        $paginas[3]["texto"] = $pagina -1;
                        $paginas[4]["valor"] = $pagina;
                        $paginas[4]["texto"] = $pagina;
                    }
                    
                    if($pagina == $total_paginas) {
                        $paginas[2]["valor"] = $pagina -1;
                        $paginas[3]["valor"] = $pagina -2;
                        $paginas[3]["texto"] = $pagina -2;
                        $paginas[4]["valor"] = $pagina -1;
                        $paginas[4]["texto"] = $pagina -1;
                        $paginas[5]["valor"] = $pagina;
                        $paginas[5]["texto"] = $pagina;
                    }
                    
                    for ($i=1;$i<6;$i++){
                        if($pagina == $paginas[$i]["valor"]){
                            $paginacion["paginas"] .= "<span>{$paginas[$i]["texto"]}</span> ";
                        } else {
                            $filtro_base64 = base64_encode($filtro);
                            //si el índice no corresponde con la página mostrada actualmente, coloco el enlace para ir a esa página
                            $paginacion["paginas"] .= <<<EOF
                                <a href='{$url}{$id}pagina={$paginas[$i]["valor"]}&filtro={$filtro_base64}'
                                    title='{$paginas[$i]["titulo"]}'>{$paginas[$i]["texto"]}</a>
EOF;
                        }
                    }
                    break;
                
            }
            
            return $paginacion;
        }catch(Exception $e){
            return "<p>Paginar Listado: ".$e->getMessage()."</p>";
        }
    }
?>