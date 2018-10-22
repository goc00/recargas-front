<?php $this->load->view("admin/includes/top_view") ?>
<div id="container">
	
	<h1>Administrador M&oacute;dulo de Recargas</h1>

	<div id="body">
		<h3>Opciones Disponibles:</h3>
		<p>
			<ol>
				<li><a href="<?= base_url() ?>admin/getAllTrxs">Generaci&oacute;n de archivo de ventas</a></li>
				<li><a href="<?= base_url() ?>admin/manageTrxs">Control de transacciones</a></li>
				<li><a href="<?= base_url() ?>admin/confBags">Configuraci&oacute;n de m&eacute;todos de pago para bolsas disponibles</a></li>
				<li><a href="<?= base_url() ?>admin/reverseTrxs">Reversar transacciones en Oneclick</a></li>
				<li>
					<h4>Administraci&oacute;n de Campa&ntilde;as</h4>
					<ul class="submenu">
						<li><a href="<?= base_url() ?>admin/listCampaign">Listar</a></li>
						<li><a href="<?= base_url() ?>admin/createCampaign">Crear</a></li>
						<li><a href="<?= base_url() ?>admin/stopCampaign">Bajar</a></li>
					</ul>
				<li>
					<h4>Administraci&oacute;n de Bolsas</h4>
					<ul class="submenu">
						<li><a href="<?= base_url() ?>admin/listBag">Listar</a></li>
						<li><a href="<?= base_url() ?>admin/addBag">Agregar</a></li>
						<li><a href="<?= base_url() ?>admin/editBag">Editar</a></li>
						<li><a href="<?= base_url() ?>admin/deleteBag">Eliminar</a></li>
					</ul>
				</li>
				
			</ol>
		</p>
	</div>

</div>
<?php $this->load->view("admin/includes/bottom_view") ?>