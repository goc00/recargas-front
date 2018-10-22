<?php $this->load->view("admin/includes/top_view") ?>
<div id="container">
	
	<h1>Autentificaci&oacute;n Sistema</h1>

	<div id="body">
		<p style="font-weight:bold">Completa los campos para acceder:</p>
		<p>
			<form method="post" action="<?= base_url() ?>admin/loginAction">
				<table>
					<tr>
						<td>Nombre Usuario:</td>
						<td><input type="text" id="username_txt" name="username_txt" /></td>
					</tr>
					<tr>
						<td>Contrase&ntilde;a:</td>
						<td><input type="password" id="password_txt" name="password_txt" /></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Ingresar" /></td>
					</tr>
				</table>
			</form>
		</p>
	</div>

</div>
<?php $this->load->view("admin/includes/bottom_view") ?>