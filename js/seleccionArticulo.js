$(document).ready(function () {
    $("#id_seccion").change(function () {

        var seccionElegida = $('#id_seccion option:selected').attr('id');
        //alert('dentro');
        $('#id_familia').children("optgroup").hide();
        $('#id_familia').children("optgroup[label='" + seccionElegida + "']").show();

    });
    $("#id_familia").change(function () {

        var familiaElegida = $('#id_familia option:selected').attr('id');
        //alert(familiaElegida);
        $('#id_grupo').children("optgroup").hide();
        $('#id_grupo').children("optgroup[label='" + familiaElegida + "']").show();

    });

});

/* function cambio(select, n) {

    var a = document.getElementById("A" + n);
    var he = document.getElementById("HE" + n);

    if (select.value == "1") {
        he.hidden = 'hidden';
        a.hidden = '';
    }
    if (select.value == "2") {
        he.hidden = '';
        a.hidden = 'hidden';
    }

}
function cambio2(select, n) {

    var a = document.getElementById("C" + n);//fuentes
    var he = document.getElementById("AR" + n);//cables
    var md = document.getElementById("MD" + n);//magentos y diferenciales

    if (select.value == "3") {
        he.hidden = 'hidden';
        md.hidden = 'hidden';
        a.hidden = '';
    }
    if (select.value == "4") {
        he.hidden = '';
        a.hidden = 'hidden';
        md.hidden = 'hidden';
    }
    if (select.value == "5") {
        md.hidden = '';
        he.hidden = 'hidden';
        a.hidden = 'hidden';
    }

}*/