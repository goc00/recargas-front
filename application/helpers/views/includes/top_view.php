<!DOCTYPE html>
<html>
    <head>
        <title>Sistema Recargas v1.0</title>
        <meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/offcanvas.css" />
    </head>
    <body>
	
	<div class="loading"><div class="loader"></div></div>
	
	<?php if($isLogged) { ?>
	<nav class="nav is-fixed" role="navigation">
		<button class="nav-toggle">
			<div class="icon-menu"> <span class="line line-1"></span> <span class="line line-2"></span> <span class="line line-3"></span> </div>
		</button>

		<div class="nav-container">
			<ul class="nav-menu menu">
				<?php if($activeCampaign) { ?>
					<li class="menu-item"><a href="<?= base_url() ?>core/exchangeCode" class="menu-link a-no-class">Canjear C&oacute;digo</a></li>
				<?php } ?>
				<li class="menu-item"><a href="<?= base_url() ?>core/bags" class="menu-link a-no-class">Bolsas de Datos</a></li>
				<li class="menu-item"><a href="<?= base_url() ?>core/logout" class="menu-link a-no-class">Desconectar</a></li>
			</ul>
		</div>
		<?php if(!is_null($aniLogged)) { ?>
			<div class="ani-logged">
				<span>N&uacute;mero conectado:</span><br />
				<span><?= $aniLogged ?></span>
			</div>
		<?php } ?>
		
	</nav>
	
	
	<?php } ?>
