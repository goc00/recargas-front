<?php
	$this->load->view("includes/top_view");
?>
<div class="table">
	<div class="td">
		<div class="container">
			<?php if(isset($title)) { ?>
				<h2><?= $title ?></h2>
			<?php } ?>
			<h4><?= $error ?></h4>
			<a href="<?= base_url() ?>core/bags">Volver al listado de bolsas</a>
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>