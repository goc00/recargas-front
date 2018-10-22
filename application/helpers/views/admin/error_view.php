<?php $this->load->view("admin/includes/top_view") ?>
<div id="container">
	
	<h1>Ha ocurrido un error en la solicitud</h1>

	<div id="body">
		<p><?= $message ?></p>
		<p><a href="javascript:history.back(1)">Volver</a></p>
	</div>

</div>
<?php $this->load->view("admin/includes/bottom_view") ?>