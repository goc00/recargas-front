<div class="table modal">
            <div class="td">
                <div class="content">
                    <div id="modal-container">
                        <h1>Algo anda mal</h1>
                        <h2>El n&uacute;mero que has ingresado es incorrecto.</h2>
                    </div>
					<div id="request-email" style="font-size:.8em; display:none">
						<div id="req-frm-email">
						
							<form id="frmReqEmail" action="<?= base_url()."core/reqEmailAction" ?>" method="post">
								<div>D&eacute;janos tu <span style="color:#f00">correo</span> y te avisaremos.</div>
								<div style="margin:10px 0">
									<input type="email" id="request-email-txt" placeholder="tucorreo@dominio.com" required />
									<button type="submit">&iexcl;Av&iacute;senme!</button>
								</div>
								<input id="no-user-txt" type="hidden" value="" />
							</form>
						
						</div>
						
						<div id="req-loading-email" style="display:none; padding:10px 0; color: #f00">Procesando, espera un momento por favor...</div>
	
					</div>
                    <a href="#" class="close">Entendido</a>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/init.js"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/js/core.js"></script>
		<script type="text/javascript" src="<?= base_url() ?>assets/js/offcanvas.js"></script>
		
		<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    </body>
</html>