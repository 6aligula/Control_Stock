    var anular_tecla_enter = true;
    var refresco_graficas = 60 * 1000;

    // DatePicker en español
    $(function ($) {
        
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        showWeek: true,
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    });

    $(document).ready(function () {
        //$("#fecha").datepicker();

        $(document).bind("contextmenu",function(e){
            //return false;
        });
            
        $(document).keypress(function(e) {
            //console.log(e.which);
            //console.log("Estado tecla enter: " + anular_tecla_enter);
            if (typeof (anular_tecla_enter) != "undefined") {
                if(anular_tecla_enter == true) {
                    if(e.which == 13) {
                    return false;
                   }
                }
            }
        });

        actualizarCabecera();
        setInterval('actualizarCabecera()',1000);
    });

    function Anular_Enter() {
        anular_tecla_enter = false;
    }

    function Activar_Enter() {
        anular_tecla_enter = true;
    }

    function actualizarCabecera(){
        var fecha    = new Date();
        var dia      = (fecha.getDate()     < 10) ? "0" + fecha.getDate()     : fecha.getDate();
        var mes      = (fecha.getMonth()+1  < 10) ? "0" + (fecha.getMonth()+1): fecha.getMonth()+1;
        var anyo     = (fecha.getFullYear() < 10) ? "0" + fecha.getFullYear() : fecha.getFullYear();
        
        var hora     = (fecha.getHours()    < 10) ? "0" + fecha.getHours()    : fecha.getHours();
        var minutos  = (fecha.getMinutes()  < 10) ? "0" + fecha.getMinutes()  : fecha.getMinutes();
        var segundos = (fecha.getSeconds()  < 10) ? "0" + fecha.getSeconds()  : fecha.getSeconds();
        
        var dia_sistema  = dia + "-" + mes + "-" + anyo;
        var hora_sistema = hora + ":" + minutos + ":" + segundos;
        
        
        $("#dia-sistema").html(dia_sistema);
        $("#hora-sistema").html(hora_sistema);
    }

    // ****************************************************************************
    // Devuelve una fecha con formato americano o europeo
    // ****************************************************************************
    function formatoFecha(fecha, formato) {
        var resultado, dia, mes, año;
        switch (formato) {
        case "formatoSQL":
            // Recibimos un formato europeo (26-07-2015) y devolvemos un formato americano (2015-07-26)
            dia = fecha.substring(0, 2);
            mes = fecha.substring(3, 5);
            año = fecha.substring(6, 10);
            resultado = año + "-" + mes + "-" + dia;
            break;
        case "formatoNormal":
            // Recibimos un formato americano (2015-07-26) y devolvemos un formato europeo (26-07-2015)
            año = fecha.substring(0, 4);
            mes = fecha.substring(5, 7);
            dia = fecha.substring(8, 10);
            resultado = dia + "-" + mes + "-" + año;
        default:

            break;
        }
        return resultado;
    }
    
    function FechaToTexto(fecha) {
        var day   = (fecha.getDate()       < 10) ? "0" + fecha.getDate()      : fecha.getDate();
        var month = ( (fecha.getMonth()+1) < 10) ? "0" + (fecha.getMonth()+1) : (fecha.getMonth()+1);
        var year  = fecha.getFullYear();
        
        return day+"-"+month+"-"+year;
    }

    // ****************************************************************************
    // Validar los campos de la página antes de guardar en la base de datos
    // ****************************************************************************
    function ValidarCampo(campo, nombre, tipo, obligatorio) {
        var control = true;
        var resultado = "";
        var expRegular = "";
        switch (tipo) {
        case "numero":
            expRegular = /^([0-9])*$/;
            if (isNaN(campo) == true || campo.length == 0 || expRegular.test(campo) == false) {
                control = false;
                resultado = "El campo numérico " + nombre + " está vacio o es negativo.\n";
            }
            break;
        case "decimal":
            expRegular = /^\d+(?:\.\d{0,2})$/;
            if (isNaN(campo) == true || campo.length == 0 || expRegular.test(campo) == false) {
                control = false;
                resultado = "El campo decimal " + nombre + " está vacio o es negativo.\n";
            }
            break;
        case "email":
            expRegular = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
            if (campo.length == 0 || expRegular.test(campo) == false) {
                control = false;
                resultado = "El campo " + nombre + " está vacio o su formato no corresponde con una dirección email.\n";
            }
            break;
        case "texto":
            if (campo == "") {
                control = false;
                resultado = "El campo " + nombre + " está vacio.\n";
            }
            break;
        default:

            break;
        }
        if (!control && !obligatorio) {
            resultado = "";
        }
        return resultado;
    }

    function gestionar_Error_Ajax(elemento, jqXHR, textStatus, errorThrown) {
        $("#"+elemento).css('color', '#ff0e0e');
        if (jqXHR.status === 0) {
            $("#"+elemento).append('Error: ' + 'No conecta: Verificar la red.');
        } else if (jqXHR.status == 404) {
            $("#"+elemento).append('Error: ' + 'Página solicitada no disponible [404]');
        } else if (jqXHR.status == 500) {
            $("#"+elemento).append('Error: ' + 'Error de servidor [500].');
        } else if (textStatus === 'parsererror') {
            $("#"+elemento).append('Error: ' + 'Falló la solicitud de JSON.');
        } else if (textStatus === 'timeout') {
            $("#"+elemento).append('Error: ' + 'Error de tiempo de espera.');
        } else if (textStatus === 'abort') {
            $("#"+elemento).append('Error: ' + 'Petición AJAX abortada.');
        } else {
            $("#"+elemento).append('Error: ' + jqXHR.responseText);
        }
    }
