//codigo en jquery
$(document).ready(function () {
    //llamada al negerador de codigo de barras pasandole las claves para generar el codigo de barras
    var id_seccion = $("#id_seccion").text();
    var id_familia = $("#id_familia").text();
    var id_grupo = $("#id_grupo").text();
    var codigo = $("#codigo").text();
    //Preparar comanda de insert de datos
    var comanda = id_seccion + ";" + id_familia + ";" + id_grupo + ";" + codigo;
    $.ajax({
        url: 'generaCodigobarras.php',
        type: "POST",
        data: { comanda_post: comanda },
        success: function (response) {
            //alert(response);
            $('#codiBarras').append(response);
        }
    });
    //Guardar la foto
    $(".upload").on('click', function () {
        //recoger datos para darle el nombre a la imagen
        var id_seccion = $("#id_seccion").text();
        var id_familia = $("#id_familia").text();
        var id_grupo = $("#id_grupo").text();
        var codigo = $("#codigo").text();
        //datos de la imagen
        var form_data = new FormData();
        var file_data = $('#image')[0].files[0];
        form_data.append('image', file_data);
        form_data.append('id_seccion', id_seccion);
        form_data.append('id_familia', id_familia);
        form_data.append('id_grupo', id_grupo);
        form_data.append('codigo', codigo);
        $.ajax({
            url: 'subeFoto.php',
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response != 0) {
                    alert(response);
                    //pego la foto en el formulario
                    $(".card-img-top").attr("src", response);
                    //recojo la ruta de la foto y la meto en un span para su envio a la BD
                    $("#rutaFoto").attr("value", response);
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });

    });


});

//codigo en js
//Restringir a un solo check box para acceso alogenos y malla
let Checked1 = null;
for (let CheckBox1 of document.getElementsByClassName('alogeno')) {
    CheckBox1.onclick = function () {
        if (Checked1 != null) {
            Checked1.checked = false;
            Checked1 = CheckBox1;
        }
        Checked1 = CheckBox1;
    }
}
let Checked2 = null;
for (let CheckBox2 of document.getElementsByClassName('malla')) {
    CheckBox2.onclick = function () {
        if (Checked2 != null) {
            Checked2.checked = false;
            Checked2 = CheckBox2;
        }
        Checked2 = CheckBox2;
    }
}
//https://www.baulphp.com/ajax-carga-de-multiples-imagenes-con-php-mysql