<?php $this->load->view("includes/top_view"); ?>
<div class="table">
	<div class="td">
		<div class="container">
			<img class="icon-pic" src="assets/images/66.png" />
			<p class="text">Ingresa tu n&uacute;mero de tel&eacute;fono</p>
			<form class="form" id="frmValidateAni" action="<?= base_url() ?>core/loginAction" method="post">
				<div class="input-container">
					<input id="ani_txt" type="number" name="number" class="input-phone-number no-spin" maxlength="8" pattern="[0-9]" placeholder="XXXXXXXX" required />
					<img src="assets/img/phone_icon_2.png" />
				</div>
				<br />
				<input type="submit" value="Aceptar" />
				<input type="hidden" id="vi" value="<?= $vendorId ?>" />
			</form>
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>
