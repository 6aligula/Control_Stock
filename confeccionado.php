<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Formulario Control Stock</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <body>

        <!--div de todo el formulario-->
        <div style="width: 800px;">
            <h3 class="titulo" style="font-weight: bold;">Formulario para otro tipo de cables</h3>
            <br>
            <!--Aqui empieza mi formulario-->
            <form action="https://ieselcalamot.com/">
                <div aria-label="First name">Campos obligatorios: *</div>
                <br>
                <div class="row g-3">
                    <div class="col">
                        <label>Sección*</label>
                        <!--Solo dos palabras patern-->
                        <input title="Introduce solo numeros" type="text" pattern="[0-9]{3}" class="form-control"
                            placeholder="sección" aria-label="First name" required>
                    </div>
                    <div class="col">
                        <label>Color Aislante*</label>
                        <input pattern="[a-zA-Z]{1,20}" title="Introduce solo letras" type="text" class="form-control"
                            placeholder="color" aria-label="Last name" required>
                    </div>
                    <div class="col" id="ape2">
                        <label>Fabricante*</label>
                        <input type="text" class="form-control" type="text" pattern="[a-zA-Z]{1,20}" title="solo letras"
                            placeholder="Fabricante" aria-label="Last name" required>
                    </div>
                </div>
                <br>

                <div class="row g-3">
                    <div class="col" id="ape2">
                        <label>Referencia fabricante*</label>
                        <input type="text" class="form-control" type="text" placeholder="Referencia Fabricante"
                            aria-label="Last name" required>
                    </div>
                    <div class="col" id="ape2">
                        <label>Proveedor*</label>
                        <input type="text" class="form-control" type="text" pattern="[a-zA-Z]{1,20}" title="solo letras"
                            placeholder="Proveedor" aria-label="Last name" required>
                    </div>
                    <div class="col" id="ape2">
                        <label>Referencia Proveedor*</label>
                        <input type="text" class="form-control" type="text" placeholder="Referencia Proveedor"
                            aria-label="Last name" required>
                    </div>
                </div>
                <br>

                <div class="row g-3">
                    <div class="col">
                        <label>Descripción</label>
                        <input type="text" class="form-control" type="text" placeholder="Descripción"
                            aria-label="Last name">
                    </div>
                    <div class="col">
                        <label>Foto</label>
                        <a href="C:\Users\Vinicio\Desktop\DAM2\Practicas\ControlStock\FormularioSystec\imagenes">Click
                            to open a folder</a>
                    </div>

                    <div class="col">
                        <label>Observaciones</label>
                        <input type="text" class="form-control" type="text" placeholder="Observaciones"
                            aria-label="Last name">
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col">
                        <label>Numero de hilos*</label>
                        <input title="Introduce solo numeros" type="text" pattern="[0-9]{3}" class="form-control"
                            placeholder="numero de hilos" aria-label="First name" required>
                    </div>
                    <div class="especialitat">
                        <label for="cdc">Tipo</label><br>
                        <td>
                            <select>
                                <option value="null"></option>
                                <option value="1">Numerada</option>
                                <option value="2">Por codigo de colores</option>
                            </select>
                        </td>
                    </div>
                    <div class="accesMitjanÇan">
                        <label for="acces" style="padding-right: 50px;"> Alogenos?</label>
                        <input type="checkbox" id="si" name="grupo2[]" class="alogeno">
                        <label for="si1" style="padding-right: 50px;"> Sí</label>
                        <input type="checkbox" id="no" name="grupo2[]" class="alogeno">
                        <label for="no1" style="padding-right: 50px;"> No</label>
                    </div>
                    <div class="accesMitjanÇan">
                        <label for="acces" style="padding-right: 50px;"> Con malla?</label>
                        <input type="checkbox" id="si" name="grupo[]" class="malla">
                        <label for="si1" style="padding-right: 50px;"> Sí</label>
                        <input type="checkbox" id="no" name="grupo[]" class="malla">
                        <label for="no1" style="padding-right: 50px;"> No</label>
                    </div>
                </div>
                <br>
                <input type="submit" value="Envia">
    </body>
    <script>
        let Checked = null;
        //The class name can vary
        for (let CheckBox of document.getElementsByClassName('malla')) {
            CheckBox.onclick = function () {
                if (Checked != null) {
                    Checked.checked = false;
                    Checked = CheckBox;
                }
                Checked = CheckBox;
            }
        }
        //Restringir a un solo check box para acceso de prueba general
        let Checked2 = null;
        //The class name can vary
        for (let CheckBox2 of document.getElementsByClassName('alogeno')) {
            CheckBox2.onclick = function () {
                if (Checked2 != null) {
                    Checked2.checked = false;
                    Checked2 = CheckBox2;
                }
                Checked2 = CheckBox2;
            }
        }
    </script>

</html>