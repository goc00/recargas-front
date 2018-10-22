<?php $this->load->view("includes/top_view"); ?>
<div class="table">
	<div class="td">
		<div class="container">
			<img class="icon-pic" src="<?= base_url() ?>assets/img/phone.png" />
			<p class="text"><?= $message ?></p>
			<form class="form" id="frmAccessCode" action="<?= $action ?>" method="post">
				<div class="input-container">
					<input type="number" name="pin_1" id="pin_1" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" required />
				</div>
				<div class="input-container">
					<input type="number" name="pin_2" id="pin_2" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" required />
				</div>
				<div class="input-container">
					<input type="number" name="pin_3" id="pin_3" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" required />
				</div>
				<div class="input-container">
					<input type="number" name="pin_4" id="pin_4" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" required />
				</div>
				<br />
				<input type="hidden" id="token" value="<?= $ani ?>" />
				<input type="hidden" id="exist" value="<?= $exist ?>" />
				<input type="submit" value="Confirmar" id="confirmar_btn" />
				<input type="button" value="Cancelar" class="back" />
			</form>
			<div style="font-size:.8em; margin-top:20px">
				<div>&iquest;No recibiste el c&oacute;digo?</div>
				<div><a id="reSendCode" href="<?= base_url() ?>core/reSendCodeAction" data-origin="code_access" ><b>REENVIAR</b></a></div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>