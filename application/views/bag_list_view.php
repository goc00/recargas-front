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
					
					<?php if((int)$bag->active == 1) { ?>
					
					<div class="ticket bag" data-id="<?= $bag->idBag ?>" onclick="ga('send', 'event', 'Buy_bag', 'click', 'buy_bag<?= $bag->idBag ?>')" >
					<?php } else { ?>
					<div class="ticket" style="cursor:default !important; background: linear-gradient(161deg,rgb(187, 187, 187) 0,rgb(187, 187, 187) 100%)">
					<?php } ?>
						<div class="img-m"><img src="<?= base_url() ?>assets/img/logo_movistar_small.png" /></div>
						<div class="left">
							<span class="name"><?= $bag->name ?></span>
							<span class="period"><?= $bag->period ?></span>
							<span class="only">S&oacute;lo para clientes Movistar</span>
						</div>
						<div class="right">
							<?php if(isset($bag->valueSale)) { ?>
								<span class="price tachado"><?= $bag->value ?></span>
								<span class="price"><?= $bag->valueSale ?></span>
							<?php } else { ?>
								<span class="price"><?= $bag->value ?></span>
							<?php } ?>
							
						</div>
						<?php if($bag->active==1) {?>
						<div class="tail"><div class="rotate"  >Comprar</div></div>
						<?php } else { ?>
						<div class="tail"><div class="rotate"  >Pronto</div></div>
						<?php } ?>
					</div>
			<?php
				}
			} else {
			?>
			<h3>Por el momento no hay bolsas disponibles.</h3>
			<?php } ?>
			
			<!-- banner -->
			<div class="banner">
				<img onclick="ga('send', 'event', 'banner', click,'banner_link')" src="<?= base_url() ?>assets/img/banner.gif" />
			</div>
		</div>
	</div>
	<input type="hidden" id="actionBuyBag" value="<?= $action ?>" />
</div>
<?php $this->load->view("includes/bottom_view"); ?>

