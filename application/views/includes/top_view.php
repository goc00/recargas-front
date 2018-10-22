<!DOCTYPE html>
<html>
    <head>
        <title>Sistema Recargas v1.0</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="description" content="GigaGo, compra Gigas a precios increíbles: Elige tu bolsa, paga ¡y listo!.">
		<meta name="keywords" content="GigaGo,Movistar,Entel,Claro,Wom,Virgin,Internet,Internet Móvil,Bolsa de Datos,Saldo,Recarga,Giga,Mega,Recargar Celular,Internet Barato">
		<meta name="author" content="DigEvo">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/offcanvas.css" />

		<link rel="stylesheet" href="<?= base_url() ?>assets/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-77043460-19', 'auto');
		ga('send', 'pageview');

		</script>
    </head>
    <body>
	
	<div class="loading"><div class="loader"></div></div>
	
	<?php
		if(isset($isLogged)) {
			if($isLogged) { 
	?>
	
	<nav class="nav is-fixed" role="navigation">
		<button class="nav-toggle">
			<div class="icon-menu"> <span class="line line-1"></span> <span class="line line-2"></span> <span class="line line-3"></span> </div>
		</button>

		<div class="nav-container">
			<ul class="nav-menu menu">
				<li class="menu-item"><a href="<?= base_url() ?>core/bags" class="menu-link a-no-class">Bolsas</a></li>
				<li class="menu-item"><a href="<?= base_url() ?>core/myPurchases" class="menu-link a-no-class">Mis Compras</a></li>
				<li class="menu-item"><a href="<?= base_url() ?>core/faq" class="menu-link a-no-class">FAQ</a></li>
				<li class="menu-item"><a href="<?= base_url() ?>core/termsAndConditions" class="menu-link a-no-class">T&eacute;rminos y Condiciones</a></li>
				<li class="menu-item"><a href="<?= base_url() ?>core/contact" class="menu-link a-no-class">Cont&aacute;ctanos</a></li>
				<?php if($activeCampaign) { ?>
					<li class="menu-item"><a href="<?= base_url() ?>core/exchangeCode" class="menu-link a-no-class">Canjear C&oacute;digo</a></li>
				<?php } ?>
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
	
	
	<?php
			}
		}
	?>
