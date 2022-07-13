<?php




/*

Plugin Name: 	PORTFOLIO-Coopérateur-CPT-SHORTCODE-Prototype
Plugin URI: 	http://github.com/GABSVN
Description: 	Si ce portofolio SHORTCODE tu utiliseras, plus de travail tu obtiendras! Que la force soit avec toi jeune padawan.. Au travail!!
Version: 		32000.0
Author: 		Gabriel_FDS
Author URI: 	http://github.com/GABSVN
Textdomain: 	GABSVN
License: 		GPLv2

*/







//Fonction principal et globale réutilisée par les autres fonctions
function display_custom_post($atts) {
	
	//Initialise le nombre de page
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;    
	
	//Attribut par défaut
	$atts = shortcode_atts    (
			array(
				'post_type' => 'post', // !!! Affiche tous les post !!!
				'posts_per_page' => 5,
				'orderby' 	=> 'post_title',
				'order' 	=> 'ASC',
				'paged'		=> $paged,
				'champs' => array(),
				 ),
				$atts
			);

	//Lancement de la requête
	$custom_post = new WP_Query( $atts );    

	//Initilisation de la variable de sortie
    $output = '';

	//Vérification si WP_Query affiche un résultat
	if ($custom_post->have_posts()) {

		//Loop, tant que posts existent - répétition intégrée (curseur)
		while ( $custom_post->have_posts() ) : $custom_post->the_post();

			//Création variable de sortie
			$output .= '<div class="custom-post-content">';
			if (count($atts['champs'])>0){
				foreach ($atts['champs'] as $c){
					$output .= '<p>';
					$output .= get_post_meta( get_the_ID(), $c, true );
					$output .= '</p>';
				}
			}
			//Photo Coopérateur
			$output .= '<div>'.get_the_post_thumbnail(get_the_ID(),'medium').'</div>';
            $output .= '</div>';
		endwhile;

		//SI besoin de plusieurs pages de navigation, Création de pages navigation
		if($atts['paged']!=""){

			//Nombre MAXIMUM de pages
			$big = 999999999; 

			//Intégration de navigation dans variable de sortie
			$output .= paginate_links( array(
								'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format' => '?paged=%#%',
								'current' => max( 1, get_query_var('paged') ),
								'total' => $custom_post->max_num_pages
								) );  
		}  

		//Réinitialisation de curseur
		wp_reset_postdata(); 

	}else{

		//Quand WP_Query retourne VIDE
		$output .= '<div class="custom-post-content">';
		$output .= 'Aucun résultat pour les posts de type '.$atts['post_type'].'.';
		$output .= '</div>';
	} 
	 
	return $output;  
}


///////////////////////////////////////////////////////////////////////

//add_shortcode( 'display_custom_post' , 'display_custom_post' );

///////////////////////////////////////////////////////////////////////




function display_Coop($atts) {
	$champs = array('nom','prenom','num_poste');

	//Création des paramètre d'affichage Coop
	$atts = shortcode_atts(array('post_type' => 'Coop','champs'=>$champs),$atts); 

	//Envoit paramètre dans fonction principal & retourne résultat
	return display_custom_post($atts);
}
//Création du shortcode [Coopérateurs] de la fonction
add_shortcode( 'Coopérateurs' , 'display_Coop' );

////////////////////////




function display_trois($troistaille) {

	//Attribut par défaut pour afficher 3 Coopérateurs par ordre de date
	//troitaille est une clef de tableau ajoutée pour savoir s'il faut faire trois tailles différentes pour les images
	//En effet, la function de base n'est pas prévue pour faire trois tailles différentes
	
	$troistaille = shortcode_atts(array('troistaille'=>false),$troistaille);

	$atts = array(	'post_type' => 'Coop',
					'posts_per_page' => 3,
					'order' 	=> 'DESC',
					'orderby' => 'date',
					'paged' => '',);	

	//Si, trois tailles différentes
	if ($troistaille['troistaille']){

		//WP_Query
		$custom_post = new WP_Query( $atts );  

		//Initilisation de variable de sortie   
		$output = '';

		//Pour savoir SI première image ou NON
		$premiereimage=true;

		//Check si WP_Query a envoyé un résultat
		if ($custom_post->have_posts()) {

			//Loop tant que posts existent - répétition intégrée (curseur)
			while ( $custom_post->have_posts() ) : $custom_post->the_post();

				//SI, première image => affichage en LARGE
				if ($premiereimage){
					$output .= "<div>".get_the_post_thumbnail(get_the_ID(),'large')."<div/>";
					$output .= "<div>";

				}else{ //Si, 2 autres images, =>  affichage petit
					$output .= get_the_post_thumbnail(get_the_ID(),'thumbnail',array('class'=>'alignleft'));
				}
				//Après première LOOP, => omettre la variable à FAUX
				$premiereimage=false;
			endwhile;
			$output .= "</div>";

			//Réinitialisation du curseur
			wp_reset_postdata(); 

		}else{//Quand WP_Query retourne VIDE
			$output .= '<div class="custom-post-content">';
			$output .= 'Aucun résultat pour les posts de type '.$atts['post_type'].'.';
			$output .= '</div>';
		}  
		return $output;  

	}else{ //SI, pas besoin de trois tailles différentes
		return display_custom_post($atts);
	}
}

//Création du shortcode [3Coop] de la fonction display_trois
add_shortcode( '3Coop' , 'display_trois' );

function display_trois_bis($atts) {

	//Attribut pour afficher 3 Coopérateurs par ordre de date
	//ISSET — Détermine si une variable est déclarée et est différente de null//
	$order = isset($atts['order']) ? $atts['order'] : '';
	$order = ($order=='first') ? 'DESC' : (($order=='last') ? 'ASC' : '');

	//////// DESC => trie par ordre déccroissant		// ASC => trie par ordre Croissant		(ASC => valeur par défaut)	/////////

	$by = isset($atts['by']) ? $atts['by'] : '';
	$by = ($by=='date') ? 'date' : (($by=='random') ? 'rand' : '');

	//EXPLODE — divise une chaîne de caractères en segments//
	$champs = isset($atts['champs']) ? explode(",",$atts['champs']) : array('');

	//$ATTS => Attributs définis par l'utilisateur dans la balise shortcode
	$atts = shortcode_atts(array('post'=>'Coop',
								 'order'=> $order,
								 'by' => $by,
								 'taxonomy' => '',
								 'taxonomy_slug' => '',
								 'champs' => $champs,),$atts);
	
							//&& => négation booléenne
	if (($atts['taxonomy']!='') && ($atts['taxonomy_slug']!='')){
		$args = array('post_type' => $atts['post'],

					'posts_per_page' => 3, //Prend que trois
					'order' 	=> $order, //Les 3 derniers
					'orderby' => $by, //Pour avoir 3 Coopérateurs ALÉATOIRES
					'paged' => '',
					'tax_query' => array( //Pour avoir les Coopérateurs que d'un secteur
						array(
							'taxonomy' => $atts['taxonomy'],
							'field'    => 'slug', //SLUG => identifiant texte unique d'une publication,ou d'une taxonomie
							'terms'    => $atts['taxonomy_slug'],
						),));
	}else{
		$args = array('post_type' => $atts['post'],
					'posts_per_page' => 3, //On en prend que trois
					'order' 	=> $order, //Les trois premiers ou derniers ?
					'orderby' => $by, //Pour avoir 3 Coopérateurs random
					'paged' => '',);
	}	

	//WP_Query
	$custom_post = new WP_Query( $args );    

	//Initilisation de la variable de sortie   
	$output = '';

	//savoir SI, première image ou NON
	$premiereimage=true;

	//SI, première petites images ou NON
	$premierepetitesimage=true;
	add_image_size( 'principal', 880, 440, array( 'left', 'top', true ) );
	add_image_size( 'vignette', 440, 220, array( 'left', 'top', true ) );

	//Vérification, SI le WP_Query a renvoyé un résultat
	if ($custom_post->have_posts()) {

		//Loop tant que des posts existent - répétion intégrée (curseur)
		while ( $custom_post->have_posts() ) : $custom_post->the_post();

			//SI, première image => affichage  GRAND
			$aff_champs="";
			if ((count($champs)>0) && !empty($champs)){ // COUNT => Compte tous les éléments d'un tableau ou dans un objet Countable
														// empty — Détermine si une variable est vide
				
				foreach ($champs as $c){
					if ($c!=""){    // ! => exclusif
						$aff_champs .= '<span>'.get_post_meta( get_the_ID(), $c, true ).' </span>';
					}
				}
				
				if (str_replace(' ','',strip_tags($aff_champs))==""){
					$aff_champs = the_title("<span>","</span>",false);
				}
				
			}else{
				$aff_champs = the_title("<span>","</span>",false);
			}
			if ($premiereimage){
				$output .= '				<div class="container-fluid">
								<div class="row">
									<div class="col-12 col-md-12 Coopérateur-vignette">	
										<div class="carousel-item active">				
											'.get_the_post_thumbnail(get_the_ID(),'principal',array('class'=>'img-fluid rounded alignleft', 'alt' => 'image')).'										
											<div class="carousel-caption">
												'.$aff_champs.'
												<p><a href="'.get_post_permalink( get_the_ID()).'" title="En savoir plus">En savoir plus</a></p>
											</div>			
										</div>							
									</div>								
								</div>
								<div class="row">
								';

			}else{ //SI, 2 autres images => affichage petit
				if ($premierepetitesimage){
					$output .= '	<div class="col-12 col-md-6 mr-5 Coopérateur-vignette-petite">
										<div class="carousel-item active">
											'.get_the_post_thumbnail(get_the_ID(),'vignette',array('class'=>'img-fluid rounded alignleft', 'alt' => 'image')).'
											<div class="carousel-caption">
												'.$aff_champs.'
												<p><a href="'.get_post_permalink( get_the_ID()).'" title="En savoir plus">En savoir plus</a></p>
											</div>
										</div>
									</div>									
									';
				}else{
					$output .= '<div class="col-12 col-md-6 Coopérateur-vignette-petite">
										<div class="carousel-item active">
											'.get_the_post_thumbnail(get_the_ID(),'vignette',array('class'=>'img-fluid rounded alignleft', 'alt' => 'image')).'
											<div class="carousel-caption">
												'.$aff_champs.'
												<p><a href="'.get_post_permalink( get_the_ID()).'" title="En savoir plus">En savoir plus</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							';
				}
				$premierepetitesimage=false;
			}
			//Après première LOOP, on met la variable à FAUX
			$premiereimage=false;
		endwhile;

		//Réinitialisation de notre curseur
		wp_reset_postdata(); 

	}else{			//Quand WP_Query retourne VIDE
		$output .= '<div class="custom-post-content">';
		$output .= 'Aucun résultat pour les posts de type '.$args['post_type'].'.';
		$output .= '</div>';
	}  
	return $output;  
}

//Création du shortcode [3Coop_bis] de la fonction display_trois
add_shortcode( '3Coop_bis' , 'display_trois_bis' );


				