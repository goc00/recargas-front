<?php $this->load->view("includes/top_view"); ?>

<div class="table">
	<div class="td">
		<div class="container">
			
			<h1 style="margin-bottom:20px">Contacto</h1>
			
			<div>
				<form id="frmContact" action="<?= base_url() ?>core/contactAction" method="post">
					<table class="table-contact">
						<tr>
							<td>Nombre:</td>
							<td class="pl"><input type="text" id="name" name="name" required /></td>
						</tr>
						<tr>
							<td>Direcci&oacute;n de correo:</td>
							<td class="pl"><input type="email" id="email" name="email" required /></td>
						</tr>
						<tr>						
							<td>Asunto:</td>
							<td class="pl">
							<select id="subject" name="subject">
							<option  value="contacto">Cont&aacute;ctanos</option>
  							<option  value="boleta">Solicita tu boleta</option>
							</select>
							</td>
						</tr>
						<tr>
							<td>Comentario:</td>
							<td class="pl"><textarea id="comments" name="comments" required></textarea></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:center !important"><input type="submit" value="Aceptar" /></td>
						</tr>
					</table>
				</form>
			</div>
			
		</div>
	</div>
</div>
<?php $this->load->view("includes/bottom_view"); ?>