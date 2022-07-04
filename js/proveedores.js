	function btn_Disponible() {
		if($("#disponible").is(':checked')) {
			$("#disponible").prop("checked", false);
			$("#imagen_disponible").attr("src", "img/off.png");
		} else {
			$("#disponible").prop("checked", true);
			$("#imagen_disponible").attr("src", "img/on.png");
		}
	}