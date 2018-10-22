<?php $this->load->view("includes/top_view"); ?>

<div class="table">
	<div class="td">
		<div class="container">
			
			<h1 style="margin-bottom:20px">Mis Compras</h1>
			
			<div>
				<table class="table-purchases">
					<thead>
						<tr>
							<th>Bolsa</th>
							<th>Fecha</th>
							<th>Monto</th>
							
						</tr>
					</thead>
					<tbody>
						<?php if($totalTrxs > 0) { ?>
						
						<?php foreach($trxs as $o) { ?>
						
						<tr>
							<td><?= $o->name ?></td>
							<td><?= $o->creationDate ?></td>
							<td><?= $o->value ?></td>
							
						</tr>
						<?php }?>
						<?php } else { ?>
						<tr>
							<td colspan="3"><?= $message ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>