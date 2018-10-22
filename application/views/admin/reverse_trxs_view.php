<?php $this->load->view("admin/includes/top_view") ?>
<div id="container">
	
	<h1>Administrador M&oacute;dulo de Recargas</h1>

	<div id="body">
		<p style="font-weight:bold">Reversa de transacciones en Oneclick:</p>
		<p>
			<form id="frmReverseTrx" action="<?= base_url() ?>admin/reverseTrxAction" method="post">
				<table cellspacing="20">
					<tr>
						<th>Orden de compra:</th>
						<td><input type="text" id="buyOrder" /></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Reversar" /><a href="<?= base_url() ?>admin/menu" style="margin-left:20px">Volver al Men&uacute;</a></td>
					</tr>
				</table>
			</form>
		</p>
	</div>

</div>

<?php $this->load->view("admin/includes/bottom_view") ?>