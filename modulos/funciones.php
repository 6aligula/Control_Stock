<?php
    
    define("GLOBAL_COLOR_FONDO_FUENTE" , array(                           // FONDO              FUENTE
                                        array("#008000", "#FFFFFF"),      // 00.Green               White
                                        array("#00FFFF", "#000000"),      // 01.Cyan                Black
                                        array("#FF4500", "#FFFFFF"),      // 02.OrangeRed           White
                                        array("#FFFF00", "#000000"),      // 03.Yellow              Black
                                        array("#00FF00", "#FFFFFF"),      // 04.Lime                White
                                        array("#0000FF", "#FFFFFF"),      // 05.Blue                White
                                        array("#8B0000", "#000000"),      // 06.DarkRed             Black
                                        array("#FFE4C4", "#000000"),      // 07.Bisque              Black
                                        array("#90EE90", "#FFFFFF"),      // 08.LightGreen          White
                                        array("#20B2AA", "#FFFFFF"),      // 09.LightSeaGreen       White
                                        array("#FA8072", "#000000"),      // 10.Salmon              Black
                                        array("#F0E68C", "#000000"),      // 11.Khaki               Black
                                        array("#808000", "#000000"),      // 12.Olive               Black
                                        array("#000080", "#FFFFFF"),      // 13.Navy                White
                                        array("#C71585", "#FFFFFF"),      // 14.MediumVioletRed     White
                                        array("#FFA500", "#FFFFFF"),      // 15.Orange              White
                                        array("#3CB371", "#FFFFFF"),      // 16.MediumSeaGreen      White
                                        array("#E6E6FA", "#000000"),      // 17.Lavender            Black
                                        array("#DC143C", "#000000"),      // 18.Crimson             Black
                                        array("#B8860B", "#FFFFFF"),      // 19.DarkGoldenrod       White
                                        array("#BDB76B", "#000000"),      // 20.DarkKhaki           Black
                                        array("#008080", "#000000"),      // 21.Teal                Black
                                        array("#800080", "#000000"),      // 22.Purple              Black
                                        array("#F4A460", "#FFFFFF"),      // 23.SandyBrown          White
                                        array("#00FA9A", "#FFFFFF"),      // 24.MediumSpringGreen   White
                                        array("#4169E1", "#FFFFFF"),      // 25.RoyalBlue           White
                                        array("#D8BFD8", "#000000"),      // 26.Thistle             Black
                                        array("#8B4513", "#FFFFFF"),      // 27.SaddleBrown         White
                                        array("#9ACD32", "#000000"),      // 28.YellowGreen         Black
                                        array("#B0E0E6", "#000000"),      // 29.PowderBlue          Black
                                        array("#FF69B4", "#000000"),      // 30.HotPink             Black
                                        array("#444444", "#FFFFFF")       // 31.CasiNegro           White
                                    )
        );
    
    
    // ***************************************************************************
    // Convierte el formato de fecha de MySQL (año/mes/dia hora/minutos/segundos)
    // en formato europeo (dia/mes/año hora/minutos/segundos).
    // Dependiendo del parametro $formato,
    // devuelve solo fecha, solo hora o la fecha completa (fecha + hora)
    // ***************************************************************************
    function Fecha_MySQL_To_Normal($fecha, $formato = "fecha") {
        $resultado = "";
        try {
            if($fecha != "") {
                $any = substr($fecha,0,4);
                $mes = substr($fecha,5,2);
                $dia = substr($fecha,8,2);
                if($formato != "fecha") {
                    $hora = substr($fecha,11,2);
                    $minutos = substr($fecha,14,2);
                    $segundos = substr($fecha,17,2);
                }
                
                switch ($formato) {
                    case "fecha":
                        $resultado = checkdate($mes, $dia, $any) ? $dia."-".$mes."-".$any : "";
                        break;
                    case "hora":
                        $resultado = $hora.":".$minutos.":".$segundos;
                        break;
                    case "fecha-hora":
                        $resultado = checkdate($mes, $dia, $any) ? $dia."-".$mes."-".$any." ".$hora.":".$minutos.":".$segundos : "";
                        break;
                }
            }
        }catch(Exception $e){
            //return "Modificar SQL: ".$e->getMessage();
            $resultado = "";
        }
        return $resultado;
    }

    // ***************************************************************************
    // Convierte el formato de fecha de Normal (dia/mes/año hora/minutos/segundos)
    // en formato MySQL (año/mes/dia hora/minutos/segundos).
    // Dependiendo del parametro $formato,
    // devuelve solo fecha, solo hora o la fecha completa (fecha + hora)
    // ***************************************************************************
    function Fecha_Normal_To_MySQL($fecha, $formato = "fecha") {
        $resultado = "";
        try {
            if($fecha != "") {
                $dia = substr($fecha,0,2);
                $mes = substr($fecha,3,2);
                $any = substr($fecha,6,4);
                if($formato != "fecha") {
                    $hora = substr($fecha,11,2);
                    $minutos = substr($fecha,14,2);
                    $segundos = substr($fecha,17,2);
                }
                
                switch ($formato) {
                    case "fecha":
                        $resultado = checkdate($mes, $dia, $any) ? $any."-".$mes."-".$dia : "";
                        break;
                    case "hora":
                        $resultado = $hora.":".$minutos.":".$segundos;
                        break;
                    case "fecha-hora":
                        $resultado = checkdate($mes, $dia, $any) ? $any."-".$mes."-".$dia." ".$hora.":".$minutos.":".$segundos : "";
                        break;
                }
            }
        }catch(Exception $e){
            //return "Modificar SQL: ".$e->getMessage();
            $resultado = "";
        }
        return $resultado;
    }

    // ***************************************************************************
    // Convierte el formato de time de MySQL (hora/minutos/segundos)
    // en formato (hora:minutos).
    // ***************************************************************************
    function Formato_Hora_Corta($hora) {
        $resultado = "";
        try {
            $tamaño = strlen($hora);
            $horas = substr($hora,0, $tamaño-4);
            $minutos = substr($hora,-4,2);
            $resultado = $horas.":".$minutos;     
        }catch(Exception $e){
            //return "Modificar SQL: ".$e->getMessage();
            $resultado = "";
        }
        return $resultado;
        
    }

    function dame_nombre_mes($mes){
        $meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio",
                          "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        return $meses[$mes];
    }

    function dame_nombre_mes_corto($mes){
        $meses = array("","ENE","FEB","MAR","ABR","MAY","JUN",
                          "JUL","AGO","SEP","OCT","NOV","DIC");
        return $meses[$mes];
    }
    
    //**************************************************
    //		Diferencia_Entre_Dos_Fechas_En_Segundos
    //
    //**************************************************
    function Diferencia_Entre_Dos_Fechas_En_Segundos($fecha_inicio, $fecha_fin){
        try{
            $diferencia = new DateTime();
            $inicio = new DateTime($fecha_inicio);
            $fin = new DateTime($fecha_fin);
            $diferencia = date_diff($inicio, $fin);
            $diferencia_en_segundos = ($diferencia->s)
                 + ($diferencia->i * 60)
                 + ($diferencia->h * 60 * 60)
                 + ($diferencia->d * 60 * 60 * 24)
                 + ($diferencia->m * 60 * 60 * 24 * 30)
                 + ($diferencia->y * 60 * 60 * 24 * 365);
            return $diferencia_en_segundos;
        }catch(Exception $e){
            return 0;
        }
    }
    
    //********************************************************************
    //		Segundos_A_Horas
    //      Recibe un numero y lo devuelve en formato horas
    //********************************************************************
    function Segundos_A_Horas($valor) {
        $resultado = "";
        $horas = floor($valor / 3600);
        $horas = ($horas < 10)  ? "0".$horas : $horas;
        
        $minutos = floor(($valor - ($horas * 3600)) / 60);
        $minutos = ($minutos < 10)  ? "0".$minutos : $minutos;
        
        $segundos = floor($valor - ($horas * 3600) - ($minutos * 60));
        $segundos = ($segundos < 10)  ? "0".$segundos : $segundos;
        //$resultado =$horas . ':' . $minutos . ":" . $segundos;
        $resultado = "{$horas}:{$minutos}:{$segundos}";
        
        return $resultado;
    }
    
    //**************************************************
    //		SegundosToEscala24Horas
    //
    //**************************************************
    function Segundos_To_Escala_24Horas($valor) {
        return ( ($valor * 24) / 86400);
    }

    /***********************************************************************************************************************
    funciones_listado_sencillo
    Recibe una consulta y devuelve un listado con enlaces 

    El código HTML devuelto será parecido a este:
        <div class="list-group">
            <a href="?id=1" class="list-group-item">Turno 1</a>
            <a href="?id=2" class="list-group-item">Turno 2</a>
            <a href="?id=n" class="list-group-item">Turno n</a>
        </div>
    /***********************************************************************************************************************/
    function funciones_listado_sencillo($listado) {
        $lista = "";
        if(!empty($listado)) {
            foreach($listado as $fila){
                $id = $fila["id"];
                $nombre = $fila["nombre"];
                $lista .= <<<EOF
                    <a href="?id={$id}" class="list-group-item">{$nombre}</a>
EOF;
            }
            $lista .= "</div>";
        }	
        return <<<EOF
            <div class="list-group">
                {$lista}
            </div>
EOF;
    }

    function funciones_listado_select_options($listado, $valor, $filaCero) {
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
                    <option value="0">"SIN ELEMENTOS"</option>
EOF;
        }
        return <<<EOF
            <div class="list-group">
                {$lista}
            </div>
EOF;
    }

    /***********************************************************************************************************************
    funciones_listado_acordeon
    Recibe una consulta y devuelve un listado agrupado por el campo id.
    Este listado tendrá el efecto acordeon:
        La información asociada a cada elemento podrá ocultarse (valor por defecto) o mostrarse

    El código HTML devuelto será parecido a este:

    <div class="list-group panel">
        <a href="#demo3" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">
            Turno 1
        </a>
        <div class="collapse" id="demo3">
            Operario 1
            Operario 2
        </div>
        <a href="#demo4" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">
            Turno 2
        </a>
        <div class="collapse" id="demo4">
            Operario 3
            Operario 4
        </div>
    </div>
    /***********************************************************************************************************************/
    function funciones_listado_acordeon($listado) {
        $lista = "";
        $id_anterior = 0;
        $x = 1;
        if(!empty($listado)) {
            foreach($listado as $fila){
                $id = $fila["id"];
                $nombre = $fila["nombre"];
                
                $detalleIzquierda = $fila["detalleIzquierda"];
                $detalleDerecha = ($fila["detalleDerecha"] == NULL) ? "" : $fila["detalleDerecha"];
                $cantidad = $fila["cantidad"];
                $activado = "";
                if(isset($fila["disponible"])) {
                    if($fila["disponible"] == 1) {
                        $activado = "<img src='img/activado.png'>";
                    } else {
                        $activado = "<img src='img/cancelado.png'>";
                    }
                } 
                
                
                if($id_anterior != $id) {
                    if($id_anterior != 0) {
                        $x++;
                        $lista .= funciones_cerrarTurno();
                        $lista .= funciones_nuevoTurno($id, $nombre, $x, $activado, $cantidad);
                    } else {
                        $lista .= funciones_nuevoTurno($id, $nombre, $x, $activado, $cantidad);
                    }
                    $id_anterior = $id;			
                }
                
                if($detalleIzquierda != "")
                $lista .= "<p class='registro-listado'>{$detalleIzquierda}<span class='flotarDepartamentoDerecha'>{$detalleDerecha}</span></p>";
            }
            $lista .= "</div>";
            
            $lista = <<<EOF
            <div class="list-group panel">
                {$lista}
            </div>
EOF;
        }	
        return $lista;
    }

    /***********************************************************************************************************************
    Listado_JQuery_Select_Option
    Recibe una consulta y devuelve un listado para un objeto select option
    que se convertirá en un objeto editableSelect
    /***********************************************************************************************************************/
    function Listado_JQuery_Select_Option($ConsultaSQL, $opcion_00, $id = 0, $primera_opcion_vacia = false){
        //$TMP = "<option title='#{$opcion_00}...#' value='0'>#{$opcion_00}...#</option>";
        
        $TMP = "";
        if($primera_opcion_vacia == true) {
            $TMP .= "<option title='#{$opcion_00}...#' value='0'></option>";
        } else {
            $TMP .= "<option title='#{$opcion_00}...#' value='0'>{$opcion_00}</option>";
        }
        $selected = "";
        $solo_lectura = "";
        $listado = consultaListadoSQL($ConsultaSQL);
        if(!empty($listado)) {
            foreach ($listado as $fila){
                if($id == $fila["id"]){
                    $selected = "selected";
                }else{
                    $selected = "";
                }
                $TMP .="<option title='{$fila["nombre"]}' value='{$fila["id"]}' {$solo_lectura} {$selected}>{$fila["nombre"]}</option>";
            }
        }
        return $TMP;
    }	

?>