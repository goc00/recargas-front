<?php $this->load->view("includes/top_view"); ?>
<div class="table">
	<div class="td">
		<div class="container">
			<img class="icon-pic" src="<?= base_url() ?>assets/img/icono1_03.png" />
			<p class="text">Ingresa el c&oacute;digo promocional</p>
			<form class="form" id="frmExchangeCode" action="<?= $action ?>" method="post">
				<div><input type="text" name="exchange_code" id="exchange_code" class="input-normal" placeholder="Ejemplo: ABC123" required /></div>
				<div>
					<input type="submit" value="Confirmar" />
					<input type="button" value="Cancelar" class="back" />
				</div>
			</form>
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>