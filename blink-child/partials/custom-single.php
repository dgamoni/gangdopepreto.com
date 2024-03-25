
<div class="inner-block gangdopepreto_fields_wrap">

<div class="gangdopepreto_fields_wrap_left">
	<?php
	$gangdopepreto_avaliacao = get_field('gangdopepreto_avaliacao', $post->ID);
	if($gangdopepreto_avaliacao): ?>
		<div class="gangdopepreto_field gangdopepreto_avaliacao">
			<p>
				<b>Avaliação:</b>
				<span class="gangdopepreto_avaliacao_img">
					<?php $x = 1;
						while ($x <= intval($gangdopepreto_avaliacao)):
						     echo '<img src="'.get_stylesheet_directory_uri().'/images/avaliacao.jpg">';
						     $x++;
						endwhile; ?>
				</span>
			</p>
		</div>
	<?php endif; ?>

	<?php
	$gangdopepreto_minimo_noites = get_field('gangdopepreto_minimo_noites', $post->ID);
	if($gangdopepreto_minimo_noites): ?>
		<div class="gangdopepreto_field gangdopepreto_minimo_noites">
			<p>
				<b>Mínimo noites:</b>
				<span><?php echo $gangdopepreto_minimo_noites; ?></span>
			</p>
		</div>
	<?php endif; ?>

	<?php
	$gangdopepreto_preco_da_estadia = get_field('gangdopepreto_preco_da_estadia', $post->ID);
	if($gangdopepreto_preco_da_estadia): ?>
		<div class="gangdopepreto_field gangdopepreto_preco_da_estadia">
			<p>
				<b>Preço da Estadia:</b>
				<span><?php echo $gangdopepreto_preco_da_estadia; ?></span>
			</p>
		</div>
	<?php endif; ?>


	<?php
	$gangdopepreto_family_friendly = get_field('gangdopepreto_family_friendly', $post->ID);
	if($gangdopepreto_family_friendly): ?>
		<div class="gangdopepreto_field gangdopepreto_family_friendly">
			<p>
				<b>Family friendly:</b>
				<span><?php echo $gangdopepreto_family_friendly; ?></span>
			</p>
		</div>
	<?php endif; ?>


	<?php
	$gangdopepreto_comes_e_bebes = get_field('gangdopepreto_comes_e_bebes', $post->ID);
	if($gangdopepreto_comes_e_bebes): ?>
		<div class="gangdopepreto_field gangdopepreto_comes_e_bebes">
			<p>
				<b>Comes e Bebes:</b>
				<span><?php echo $gangdopepreto_comes_e_bebes; ?></span>
			</p>
		</div>
	<?php endif; ?>


	<?php
	$gangdopepreto_paisagens = get_field('gangdopepreto_paisagens', $post->ID);
	if($gangdopepreto_paisagens): ?>
		<div class="gangdopepreto_field gangdopepreto_paisagens">
			<p>
				<b>Paisagens:</b>
				<span><?php echo $gangdopepreto_paisagens; ?></span>
			</p>
		</div>
	<?php endif; ?>


	<?php
	$gangdopepreto_pessoas = get_field('gangdopepreto_pessoas', $post->ID);
	if($gangdopepreto_pessoas): ?>
		<div class="gangdopepreto_field gangdopepreto_pessoas">
			<p>
				<b>Pessoas:</b>
				<span><?php echo $gangdopepreto_pessoas; ?></span>
			</p>
		</div>
	<?php endif; ?>

</div><!-- end left -->

<div class="gangdopepreto_fields_wrap_right">

	<?php
	$displaynone = true;
	$gangdopepreto_regiao = get_field('gangdopepreto_regiao', $post->ID);
	if($gangdopepreto_regiao && $displaynone): ?>
		<div class="gangdopepreto_field gangdopepreto_regiao">
			<p>
				<b>Região:</b>
				<span><?php echo $gangdopepreto_regiao; ?></span>
			</p>
		</div>
	<?php endif; ?>
	
	<?php
	$gangdopepreto_nome_do_local = get_field('gangdopepreto_nome_do_local', $post->ID);
	if($gangdopepreto_nome_do_local): ?>
		<div class="gangdopepreto_field gangdopepreto_nome_do_local">
			<p>
				<b>Nome do local:</b>
				<span><?php echo $gangdopepreto_nome_do_local; ?></span>
			</p>
		</div>
	<?php endif; ?>

	<?php
	$gangdopepreto_morada = get_field('gangdopepreto_morada', $post->ID);
	if($gangdopepreto_morada): ?>
		<div class="gangdopepreto_field gangdopepreto_morada">
			<p>
				<b>Morada:</b>
				<span><?php echo $gangdopepreto_morada; ?></span>
			</p>
		</div>
	<?php endif; ?>

	<?php
	$gangdopepreto_telefone = get_field('gangdopepreto_telefone', $post->ID);
	if($gangdopepreto_telefone): ?>
		<div class="gangdopepreto_field gangdopepreto_telefone">
			<p>
				<b>Telefone:</b>
				<span><?php echo $gangdopepreto_telefone; ?></span>
			</p>
		</div>
	<?php endif; ?>

	<?php
	$gangdopepreto_email = get_field('gangdopepreto_e-mail', $post->ID);
	if($gangdopepreto_email): ?>
		<div class="gangdopepreto_field gangdopepreto_email">
			<p>
				<b>E-mail:</b>
				<a href="mailto:<?php echo $gangdopepreto_email; ?>"><?php echo $gangdopepreto_email; ?></a>
			</p>
		</div>
	<?php endif; ?>

	<?php
	$gangdopepreto_site = get_field('gangdopepreto_site', $post->ID);
	if($gangdopepreto_site): ?>
		<div class="gangdopepreto_field gangdopepreto_site">
			<p>
				<b>Site:</b>
				<a href="<?php echo $gangdopepreto_site; ?>"><?php echo $gangdopepreto_site; ?></a>
			</p>
		</div>
	<?php endif; ?>

</div>

<div class="clrf"></div>

	<?php
	$gangdopepreto_localizacao = get_field('gangdopepreto_localizacao', $post->ID);
	if( !empty($gangdopepreto_localizacao) ): ?>
		<?php get_template_part( 'partials/map', 'fields' ); ?>
	<?php endif; ?>
		
</div>
<!-- end inner-block  gangdopepreto_fields_wrap -->




<?php
// 1
// Avaliação
// gangdopepreto_avaliacao
// 2
// Região
// gangdopepreto_regiao
// 3
// Localização
// gangdopepreto_localizacao
// 4
// Nome do local
// gangdopepreto_nome_do_local
// 5
// Morada
// gangdopepreto_morada
// 6
// Telefone
// gangdopepreto_telefone
// 7
// E-mail
// gangdopepreto_e-mail
// 8
// Site
// gangdopepreto_site
// 9
// Mínimo noites
// gangdopepreto_minimo_noites
// 10
// Preço da Estadia
// gangdopepreto_preco_da_estadia
// 11
// Family friendly
// gangdopepreto_family_friendly
// 12
// Comes e Bebes
// gangdopepreto_comes_e_bebes
// 13
// Paisagens
// gangdopepreto_paisagens
// 14
// Pessoas
// gangdopepreto_pessoas
// 15
// Contacto
// gangdopepreto_contacto
// 16
// Email contacto
// gangdopepreto_email_contacto
// 17
// Email para reservas via parceria
// gangdopepreto_email_para_reservas_via_parceria

	$gangdopepreto_contacto = get_field('gangdopepreto_contacto', $post->ID);
	if($gangdopepreto_contacto): 
		//echo $gangdopepreto_contacto;
	endif; 

	$gangdopepreto_email_contacto = get_field('gangdopepreto_email_contacto', $post->ID);
	if($gangdopepreto_email_contacto): 
		//echo $gangdopepreto_email_contacto;
	endif; 

	$gangdopepreto_email_para_reservas_via_parceria = get_field('gangdopepreto_email_para_reservas_via_parceria', $post->ID);
	if($gangdopepreto_email_para_reservas_via_parceria): 
		//echo $gangdopepreto_email_para_reservas_via_parceria;
	endif; 