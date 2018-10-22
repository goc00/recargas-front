<?php $this->load->view("admin/includes/top_view") ?>
<div id="container">
	
	<h1>Administrador M&oacute;dulo de Recargas</h1>

	<div id="body">
		<p style="font-weight:bold">Configuraci&oacute;n de M&eacute;todos de Pago para Bolsas Disponibles:</p>
		<p>
			<form id="frmConfPTBag" action="<?= base_url() ?>admin/confPtsBagAction" method="post">
				<table cellspacing="20">
					<tr>
						<th>Bolsas disponibles</th>
						<th>M&eacute;todos de Pago</th>
						<th></th>
					</tr>
					<tr>
						<td valign="top">
						<?php
						if(count($bags) > 0) {
							foreach($bags as $bag) {
						?>
							<div class="bag_name" data-id="<?= $bag->idBag ?>"><?= $bag->name ?></div>
						<?php
							}
						}
						?>
						</td>
						<td valign="top">
						<?php
						if(count($listPayments) > 0) {
							foreach($listPayments as $listPayments) {
						?>
							<div class="pt_name"><input type="checkbox" name="pt" value="<?= $listPayments->idPaymentType ?>"> <?= $listPayments->name ?></div>
						<?php
							}
						}
						?>
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Guardar" /><a href="<?= base_url() ?>admin/menu" style="margin-left:20px">Volver al Men&uacute;</a></td>
					</tr>
				</table>
			</form>
		</p>
	</div>

</div>
<input type="hidden" id="loadPTsBag" value="<?= $action ?>" />
<?php $this->load->view("admin/includes/bottom_view") ?>