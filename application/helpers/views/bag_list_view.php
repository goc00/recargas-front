<?php $this->load->view("includes/top_view"); ?>
<div class="table">
	<div class="td">
		<div class="container">
			
			<h1>Selecciona tu bolsa</h1>
			
			<?php if(!is_null($campaign)) { ?>
			<div class="code-active">
				<div>C&oacute;digo Activo: <b><?= $campaign->code ?></b></div>
				<div class="code-active-2">(<?= $campaign->name ?>)</div>
				<input type="hidden" id="ca" value="<?= $campaign->codeEncrypted ?>" />
			</div>
			<?php } ?>
			
			<?php
			if(!is_null($bags)) {
				foreach($bags as $bag) {
			?>
					
					<!-- ticket -->
					<div class="ticket bag" data-id="<?= $bag->idBag ?>">
						<div class="left">
							<span class="name"><?= $bag->name ?></span>
							<span class="period"><?= $bag->period ?></span>
						</div>
						<div class="right">
							<?php if(isset($bag->valueSale)) { ?>
								<span class="price tachado"><?= $bag->value ?></span>
								<span class="price"><?= $bag->valueSale ?></span>
							<?php } else { ?>
								<span class="price"><?= $bag->value ?></span>
							<?php } ?>
							
						</div>
						<div class="tail"><div class="rotate">Comprar</div></div>
					</div>
			<?php
				}
			} else {
			?>
			<h3>Por el momento no hay bolsas disponibles.</h3>
			<?php } ?>
			
			<!-- banner -->
			<div class="banner">
				<img src="<?= base_url() ?>assets/img/banner.png" />
			</div>
		</div>
	</div>
	<input type="hidden" id="actionBuyBag" value="<?= $action ?>">
</div>
<?php $this->load->view("includes/bottom_view"); ?>