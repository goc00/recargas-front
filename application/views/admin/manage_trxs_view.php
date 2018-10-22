<?php $this->load->view("admin/includes/top_view") ?>

<div id="container">
	
	<h1>
	Control de Transacciones  
		<div align="left">  
			<form action="/admin/manageTrxs" method="get">
				<input type="text" name="s" placeholder="Buscar.."> 
				<input type="submit" value="Buscar">
			</form> 
		</div> 
		<div align="left">  
			<button id="donwload">Export excel</button>
		</div> 
	</h1>
	<div id="body">

		<table style="width:100%" id="table_trx">
			<tr style="text-align:left">
				<th>ID</th>
				<th>Ani</th>
				<th>Bolsa</th>
				<th>Monto</th>
				<th>Fecha</th>
				<th>Estado Bolsa</th>
				<th>Estado Pago</th>
				<th>N&deg; de Intentos Reproceso</th>
				<th>Detalle</th>
				<th>Acciones</th>
			</tr>
			
			<?php if($totalTrxs > 0) { ?>
			
			<?php foreach($trxs as $o) { ?>
				
			<tr>
				<td><?= $o->idTrx ?></td>
				<td><?= $o->ani ?></td>
				<td><?= $o->name ?></td>
				<td><?= $o->value ?></td>
				<td><?= $o->creationDate ?></td>
				<td><?php if($o->stateId == 1){?><?= $o->stateName?><?php } ?></td>
				<td><?php if($o->stateId == 2 || $o->stateId == 3 ){?><?= $o->stateName?><?php } ?></td>
				<td><?= $o->attemptsNumber ?></td>
				<td><?= $o->stateDescription ?></td>
				<?php if(!is_null($o->doAction)) { ?>
					<td>
						<form action="<?= $o->doAction ?>" method="POST">
							<input type="hidden" name="idTrx" value="<?= $o->idTrx ?>" />
							<button type="submit"><?= $o->actionName ?></button>
						</form>
					</td>
				<?php } else { ?>
					<td></td>
				<?php } ?>
				
			</tr>
			<?php } ?>
			
			<?php } else { ?>
			<tr>
				<td colspan="7"><?= $message ?></td>
			</tr>
			<?php } ?>
	
		</table>
				
		<tr>
				<td colspan="7"><a href="<?= base_url() ?>admin/menu" style="margin-left:20px">Volver al Men&uacute;</a></td>
			    
			</tr>
			
	</div>

</div>


     
<?php $this->load->view("admin/includes/bottom_view") ?>


<script>
      	// button click
$('#donwload').on('click',function(){
	// get the table id
	$("#table_trx").table2excel({
		exclude: ".noExl",
		name: "Worksheet Name",
		filename: "Reporte" //do not include extension
	}); 
});
</script>