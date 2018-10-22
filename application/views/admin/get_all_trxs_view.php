<?php $this->load->view("admin/includes/top_view") ?>
<div id="container">
	
	<h1>Administrador M&oacute;dulo de Recargas</h1>

	<div id="body">
		<p style="font-weight:bold">Generación de archivo de ventas</p>
		<p>
			<form action="<?= base_url() ?>admin/getAllTrxsAction" method="post">
				<table cellspacing="20">
					<tr>
						<th>Generar archivo:</th>
						<td><input type="submit" value="Generar" /></td>
					</tr>
					<tr>
						<td colspan="2"><a href="<?= base_url() ?>admin/menu" style="margin-left:20px">Volver al Men&uacute;</a></td>
					</tr>
				</table>
			</form>
		</p>
	</div>

</div>

<?php $this->load->view("admin/includes/bottom_view") ?>