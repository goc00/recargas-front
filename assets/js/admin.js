/* ----------------------------------------------
 * Desarrollado por Gastón Orellana C.
 * ---------------------------------------------- */
$(document).ready(function() {
	
	// Selección de bolsa
	$(document).on("click", ".bag_name", function() {
		
		var o = $(this);
		var id = o.data("id");
		
		var bagActive = $(".bag_name[class*='bag_name_selected']");
		var idBagActive = bagActive.data("id");
		
		if(typeof idBagActive != "undefined") {
			
			// Si son distintos, cambia la selección al div que estoy clickeando
			if(idBagActive != id) {
				bagActive.removeClass("bag_name_selected");
				loadPaymentMethodsBag(o);
			}
			
		} else {
			loadPaymentMethodsBag(o);
		}
		
	});
	
	// Reversa transacción en Oneclick
	$("#frmReverseTrx").submit(function() {
		
		var frm = $(this);
		var val = $.trim($("#buyOrder").val());
		if(val != "") {
			
			var r = confirm("¿Estás seguro de reversar la transacción?, el proceso es IRREVERSIBLE.");
			if(r == true) {
				var obj = {
						"buyOrder" : $("#buyOrder").val()
					};

				var func = function($data) {
					alert($data.message);
					if($data.code == 0) {
						$("#buyOrder").val("");
					}
					
				};
				
				doPost(frm.attr("action"), obj, func);
			}
			
		}
		

		return false;
	});
	
	// Configura métodos de pago para bolsa
	$("#frmConfPTBag").submit(function() {
		
		var frm = $(this);
		
		var bagActive = $(".bag_name[class*='bag_name_selected']");
		var idBagActive = bagActive.data("id");
		var arr = [];
		var opts = $("input[type=checkbox]:checked");
		
		//console.log(opts.length);
		opts.each(function(i,e) {
			arr.push($(e).val());
		});
		
		var obj = {
					"idBag" : typeof idBagActive == "undefined" ? "" : idBagActive,
					"pts" : arr
				};

		var func = function($data) {
			
			alert($data.message);
			
			/*if($data.code == 0) {
				//location.href = $data.result;
			} else {
				// Error
				alert($data.message);
			}*/
		};
		
		doPost(frm.attr("action"), obj, func);
		
		return false;
	});
	
	
	// Se trae los métodos de pago para la bolsa seleccionada
	function loadPaymentMethodsBag(oBag) {
		
		// Métodos de pago
		var pts = $("input[type=checkbox]");
		
		oBag.addClass("bag_name_selected"); // "selecciona" bolsa
		
		var obj = {
					"idBag" : oBag.data("id")
				};
				
		var func = function($data) {
			if($data.code == 0) {
				
				// Limpia selecciones
				cleanCheckboxes(pts);
				
				// Marca las opciones relacionadas
				var objs = $data.result;
				var l = objs.length;
				for(var i=0;i<l;i++) {
					
					var ele = objs[i];
					var id = ele.idPaymentType;
					
					// Considera elemento por su id
					pts.each(function(i, e) {
						if($(e).val() == id) {
							$(e).prop('checked', true);
							return false;
						}
						//console.log(id);
					});
					
				}
				
				//console.log($data.result);
				
			} else if ($data.code == 1002) {
				// No hay métodos disponibles
				cleanCheckboxes(pts);
			} else {
				// Error
				alert($data.message);
			}
		};
		
		doPost($("#loadPTsBag").val(), obj, func);
		
	}
	
	function cleanCheckboxes(pts) {
		pts.each(function(i, e) {
			$(e).prop('checked', false);
		});
	}
	
	
	// Función para envío POST mediant JSON
	function doPost($action, $obj, $function) {
		$.post(
			$action,
			$obj,
			function(data) { $function(data) },
			"json"
		);
	}
	
});