<?php
	$this->load->view("includes/top_view");
?>
<div class="table">
	<div class="td">
		<div class="container">
			<?php if(isset($title)) { ?>
				<h3><?= $title ?></h3>
			<?php } ?>
			<h4><?= $error ?></h4>
			<div>
				<div>
					<a href="<?= base_url() ?>">Retornar a Inicio</a>&nbsp;<span style="font-size:.7em">&brvbar;</span>&nbsp;<a href="<?= base_url() ?>core/bags">Volver a Listado de Bolsas</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>