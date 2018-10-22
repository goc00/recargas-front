<?php $this->load->view("admin/includes/top_view") ?>
<div id="container">
	
	<h1>Administrador M&oacute;dulo de Recargas</h1>

	<div id="body">
		<p style="font-weight:bold">Agregar nueva bolsa:</p>
		<p>
			<form id="frmAddBag" action="<?= base_url() ?>admin/addBagAction" method="post">
				<table cellspacing="20">
					<tr>
						<th>Nombre</th>
						<td><input id="name_bag_txt" type="text" /></td>
					</tr>
					<tr>
						<th>C&oacute;digo</th>
						<td><input id="code_bag_txt" type="text" /></td>
					</tr>
					<tr>
						<th>Per&iacute;odo</th>
						<td><input id="period_bag_txt" type="text" /> d&iacute;a(s)</td>
					</tr>
					<tr>
						<th>Monto ($)</th>
						<td><input id="value_bag_txt" type="text" /></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Guardar" /><a href="<?= base_url() ?>admin/menu" style="margin-left:20px">Volver al Men&uacute;</a></td>
					</tr>
				</table>
			</form>
		</p>
	</div>

</div>
<?php $this->load->view("admin/includes/bottom_view") ?>