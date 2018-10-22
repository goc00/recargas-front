/* ----------------------------------------------
 * Desarrollado por Gastón Orellana C.
 * ---------------------------------------------- */
$(document).ready(function() {
	
	var processing = false; // semáforo para controlar procesos
	var closing = false;	// semáforo para despliegue de loading
	
	// Formulario de contacto
	$("#frmContact").submit(function(e) {
		
		e.preventDefault();
		
		if(!processing) {
			
			engage();
			
			var frm = $(this);
			var obj = {
						"name" : $("#name").val(),
						"email" : $("#email").val(),
						"subject" : $("#subject").val(),
						"comments" : $("#comments").val()
					};
			
			var func = function($data) {
				var $link = true;
				openDialog($data.message, "Aceptar" , false , $link);
				release();

			};
			
			doPost(frm.attr("action"), obj, func);
			
		}
		
	});
	
	
	// Login
	$("#frmValidateAni").submit(function(e) {
		
		e.preventDefault();
		
		if(!processing) {
			
			engage();
			
			var frm = $(this);
			var obj = {
						"ani" : $("#ani_txt").val(),
						"vi" : $("#vi").val()
					};
			
			var func = function($data) {
				if($data.code == 0) {
					// Usuario es Movistar
					location.href = $data.result;
				} else if($data.code == 1) {
					// Usuario NO es Movistar, solicita email
					// Pasa acceso para vincular request
					$("#no-user-txt").val($data.access);
					openDialog($data.message, "Aceptar", true);
					release();
				} else {
					// Error
					openDialog($data.message, "Aceptar");
					release();
				}
				
	
			};
			
			doPost(frm.attr("action"), obj, func);
			
		}
		
	});
	
	// Request de email
	$("#frmReqEmail").submit(function(e) {
		
		e.preventDefault();
		
		var $div = $("#request-email");
		var $divCont = $("#req-frm-email");
		var $divProcess = $("#req-loading-email");
		var frm = $(this);

		if(!processing) {
			
			// Oculta formulario...
			// Bloquea proceso
			processing = true;
			
			$divCont.hide();
			$divProcess.show();

			var obj = {
				"email" : $("#request-email-txt").val(),
				"access" : $("#no-user-txt").val()
			};
			
			var func = function($data) {
				
				// Mensaje de respuesta
				$divProcess.html($data.message);
				
				if($data.code == 0) {					
					$divProcess.css({"background-color":"#0a0", "color":"#fff"});
				} else {
					$divProcess.css({"background-color":"#a00", "color":"#fff"});
					
				}
				
				// Libera proceso
				processing = false;
	
			};
			
			doPost(frm.attr("action"), obj, func);
			
		}
		
	});
	
	// Valida código de autorización (acceso)
	$("#frmAccessCode").submit(function(e) {
		
		e.preventDefault();
		
		if(!processing) {
			
			engage();
			
			var frm = $(this);
			var obj = {
						"pin_1" : $("#pin_1").val(),
						"pin_2" : $("#pin_2").val(),
						"pin_3" : $("#pin_3").val(),
						"pin_4" : $("#pin_4").val(),
						"token" : $("#token").val(),
						"exist" : $("#exist").val()
					};
			
			var func = function($data) {
				if($data.code == 0) {
					location.href = $data.result;
				} else {
					release();
					openDialog($data.message, "Aceptar");
				}
			};
			
			doPost(frm.attr("action"), obj, func);
			
		}

	});
	
	// Código de seguridad
	$("#frmSecurityCode").submit(function(e) {
		
		e.preventDefault();
		
		if(!processing) {
			
			engage();
			
			var frm = $(this);
			var obj = {
						"pin_1" : $("#pin_1").val(),
						"pin_2" : $("#pin_2").val(),
						"pin_3" : $("#pin_3").val(),
						"pin_4" : $("#pin_4").val(),
						
						"pin_1b" : $("#pin_1b").val(),
						"pin_2b" : $("#pin_2b").val(),
						"pin_3b" : $("#pin_3b").val(),
						"pin_4b" : $("#pin_4b").val()
					};
			
			var func = function($data) {
				if($data.code == 0) {
					openDialog("Código creado satisfactoriamente", "¡Perfecto!");
				} else {
					openDialog($data.message, "Aceptar");
				}
				release();
			};
			
			doPost(frm.attr("action"), obj, func);
		}
		
	});
	
	
	// Listado de bolsas
	$(document).on("click", ".bag", function() {
		
		if(!processing) {
			
			engage();
		
			var ele = $(this);
			var action = $("#actionBuyBag").val();
			
			var obj = {
						"bag" : ele.data("id"),
						"ca" : ($("#ca").length > 0) ? $("#ca").val() : ""
					};
			
			var func = function($data) {
				if($data.code == 0) {
					location.href = $data.result;
				} else {
					// Si llega error = 1000, redirige a result
					if($data.code == 1000) {
						location.href = $data.result;
					} else {
						release();
						openDialog($data.message, "Aceptar");
					}	
				}
			};
			
			doPost(action, obj, func);
			
		}
	
	});
	
	
	// Tipos de pago (canales)
	$(document).on("click", ".payment_type", function(e) {
		e.preventDefault();
		
		engage();
		
		var ele = $(this);
		
		// Pasa valor de selección a campo
		$("#option").val(ele.data("id"));

		// Envío
		$("#frmPayment").submit();
	});
	
	
	// Resolicita el envío de código de autorización
	$(document).on("click", "#reSendCode", function(e) {
		
		e.preventDefault();
		
		if(!processing) {
			
			engage();
		
			var $action = $(this).attr("href");
			
			// Cerrar modal
			$('.modal').fadeOut(300, function() {
				
				var $ani = ($("#ani_txt").length > 0) ? $("#ani_txt").val() : 0;
				var $token = ($("#token").length > 0) ? $("#token").val() : 0;
				
				// (typeof origin == "undefined")
				var obj = {
						"ani" : $ani,
						"token" : $token
					};

				var func = function($data) {
					release();
					openDialog($data.message, "Aceptar");
				};
				
				doPost($action, obj, func);
				
			});
		}
	});
	
	
	
	
	// Ingreso de código promocional
	$("#frmExchangeCode").submit(function() {
		
		if(!processing) {
			
			engage();
			
			var frm = $(this);
			var obj = {
						"exchange_code" : $("#exchange_code").val()
					};
			
			var func = function($data) {
				if($data.code == 0) {
					// Hace redirección a bolsa con código aplicado
					location.href = $data.result;
				} else {
					release();
					openDialog($data.message, "Aceptar");
				}
			};
			
			doPost(frm.attr("action"), obj, func);
			
		}

		return false;
		
	});
	
	
	// Desuscribe al usuario de Oneclick
	$(document).on("click", "#unsusbcribeOneclick", function(e) {
		
		e.preventDefault();
		
		var obj = $(this);
	
		if(!processing) {
			
			engage();
			
			var func = function($data) {
				release();
				openDialog($data.message, "Aceptar");
			};
			
			doPost(obj.data("action"), null, func);
			
		}
		
	});
	
	// Luego de los N dígitos, pasa al botón aceptar automáticamente
	// Bloquea opción de copy & paste
	$('#ani_txt').bind("cut copy paste",function(e) {
        e.preventDefault();
    });
	$("#ani_txt").keyup(function() {
		
		var $max = this.maxLength;
		
		if (this.value.length > $max) {
			var $val = $(this).val();
			var $newVal = $val.substring(0, $max);
			$(this).val($newVal);
		}
		
		if (this.value.length == $max) {
			$("input[type='submit']").focus();
		}
		
	});
	
	
	// Botón volver
	$(".back").click(function(e) {
		e.preventDefault();
		engage();
		window.history.back();
	});
	
	if($("#accordion").length > 0) $("#accordion").accordion();
	
	
	// --------------------------------------------------
	
	function engage() {
		processing = true;
		$('.loading').fadeIn(function() {
			if(!closing) {
				$(this).css("display", "table");
			}
		});
	}
	
	function release() {
		processing = false; // en respuesta libera el proceso
		closing = true;
		$('.loading').fadeOut(function() {
			closing = false;
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
	
	function move(origen, target) {
		$("body").on("keyup mouseup", origen, function(e) {
			if ($(e.target).val().length == 1) {
				$(target).focus();
			}
		});
	}
	

	// Avance de los inputs de ingreso código
	move("#pin_1", "#pin_2");
	move("#pin_2", "#pin_3");
	move("#pin_3", "#pin_4");
	//move("#pin_4", "#confirmar_btn");
	move("#pin_1b", "#pin_2b");
	move("#pin_2b", "#pin_3b");
	move("#pin_3b", "#pin_4b");
	
});