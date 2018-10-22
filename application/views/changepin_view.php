<?php $this->load->view("includes/top_view"); ?>
<div class="table">
	<div class="td">
		<div class="container">
			<h1 class="main-title">C&oacute;digo PIN de seguridad</h1>
			<img class="icon-pic" src="<?= base_url() ?>assets/img/phone.png" />
			<form id="frmSecurityCode" class="form" method="post" action="<?= $action ?>">
				<p class="text">Puedes modificar tu PIN de seguridad</p>
				<div class="input-container">
					<input type="number" id="pin_1" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<div class="input-container">
					<input type="number" id="pin_2" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<div class="input-container">
					<input type="number" id="pin_3" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<div class="input-container">
					<input type="number" id="pin_4" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<p class="text next-block">Repite tu nuevo PIN de seguridad</p>
				<div class="input-container">
					<input type="number" id="pin_1b" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<div class="input-container">
					<input type="number" id="pin_2b" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<div class="input-container">
					<input type="number" id="pin_3b" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<div class="input-container">
					<input type="number" id="pin_4b" class="input-pin-number no-spin" min="0" max="9" pattern="[0-9]" placeholder="&times;" />
				</div>
				<p class="text next-block">No olvides este PIN, ya que lo utilizaremos para confirmar tus compras.</p>
				<input type="submit" value="Confirmar" />
				<input type="button" value="Cancelar" class="back" />
				<br />
				<!-- <a href="#">No deseo utilizar PIN de seguridad</a> -->
			</form>
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>