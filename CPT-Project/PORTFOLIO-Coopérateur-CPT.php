<?php



/*

Plugin Name: 	PORTFOLIO-Coopérateur-CPT-plug-Prototype
Plugin URI: 	http://github.com/GABSVN
Description: 	Si ce portofolio CPT tu utiliseras, plus de travail tu obtiendras! Que la force soit avec toi jeune padawan.. Au travail!!
Version: 		32000.0
Author: 		Gabriel_FDS
Author URI: 	http://github.com/GABSVN
Textdomain: 	GABSVN
License: 		GPLv2

*/





function wpm_custom_post_type() {

	// Configurations des différentes dénominations des CPTs affichées dans l'administration
	$labels = array(

		// Nom au PLURIEL
		'name'                => _x( 'Coopérateur-trices', 'Post Type General Name'),

		// Nom au SINGULIER
		'singular_name'       => _x( 'Coopérateur-trice', 'Post Type Singular Name'),

		// Libellé affiché dans le menu
		'menu_name'           => __( 'Coopérateur-trices'),

		// Différents libellés de l'administration
		'all_items'           => __( 'Tout-e-s les Coopérateur-trices'),
		'view_item'           => __( 'Voir les Coopérateur-trices'),
		'add_new_item'        => __( 'Ajouter un-e nouveau-velle Coopérateur-trice'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer le/la Coopérateur-trice'),
		'update_item'         => __( 'Modifier le/la Coopérateur-trice'),
		'search_items'        => __( 'Rechercher un-e Coopérateur-trice'),
		'not_found'           => __( 'Non trouvé-e'),
		'not_found_in_trash'  => __( 'Non trouvé-e dans la corbeille'),
	);
	
	// Configuration d'options supplémentaires pour le CPT
	
	$args = array(
		'label'               => __( 'Coopérateur-trices'),
		'description'         => __( 'Tous sur les Coopérateur-trices'),
		'labels'              => $labels,
		'menu_icon'           => 'dashicons-smiley',

		// Définition d'options disponibles dans l'éditeur du CPT ( titre, auteur...)
		'supports'            => array('thumbnail', ),

		/* 
		* Différentes options supplémentaires
        */	
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
        'show_in_rest' => true,
		'menu_position' => null,
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
        'rewrite'			  => array( 'slug' => 'Coop'),
        'capability_type' => 'post',

	);
	
	// Enregistrement du CPT, nommé "Portfolio", avec ses arguments
	register_post_type( 'Coop', $args );

}

add_action( 'init', 'wpm_custom_post_type', 0 );

//Utile si on veut mettre des indications de valeurs dans les champs
/*add_filter( 'enter_title_here', function( $title ) {
    $javascript = get_current_javascript();

    if  ( 'Coop' == $javascript->post_type ) {
        $title = 'Mettre le nom ici';
    }

    return $title;
} );*/

add_action("admin_init", "admin_init");

function admin_init(){
    add_meta_box("info_Coop", "Info Coop", "aff_info_Coop", "Coop", "normal", "low");
}

function aff_info_Coop() {
    global $post;

    $custom = get_post_custom($post->ID);

	// ISSET — Détermine si une variable est déclarée et est différente de null
    $nom = isset( $custom["nom"][0] ) ? $custom["nom"][0] : "";
    $prenom = isset( $custom["prenom"][0] ) ? $custom["prenom"][0] : "";
    $num_poste = isset( $custom["num_poste"][0] ) ? $custom["num_poste"][0] : "";

	///// $AFF ??? /////
    $aff='<p><label>Nom</label><br />';
    $aff.='<input type="text" id="nom" class="regular-text" name="nom" placeholder="';
    if(!isset( $custom["nom"][0] )){ $aff.="Nom";} 
    $aff.='" required minlength="4" maxlength="50" size="10" value="'.$nom.'">';

    $aff.='<p><label>Prénom</label><br />';
    $aff.='<input type="text" id="prenom" class="regular-text" name="prenom" placeholder="';
    if(!isset( $custom["prenom"][0] )){ $aff.="Prénom";} 
    $aff.='" required minlength="4" maxlength="50" size="10" value="'.$prenom.'">';

    $aff.='<p><label>Numéro de poste :</label><br />';
    $aff.='<input type="text" id="num_poste" class="regular-text" name="num_poste" placeholder="';
    if(!isset( $custom["num_poste"][0] )){ $aff.="num. poste";} 
    $aff.='" required minlength="4" maxlength="4" size="4" value="'.$num_poste.'">';

	// echo — Affiche une chaîne de caractères. N'est pas une fonction mais une construction du langage
    echo $aff;
}

function save_details(){
    global $post;
    $title="";

    if ( isset( $_REQUEST['nom'] ) ) {
        update_post_meta($post->ID, "nom", sanitize_text_field($_POST["nom"])); 	//sanitize_text_field( chaîne  $str  ) — Nettoie une chaîne à partir de l'entrée de l'utilisateur ou de la base de données.
        $title=sanitize_text_field($_POST["nom"])." ";
    }
    if ( isset( $_REQUEST['prenom'] ) ) {
        update_post_meta($post->ID, "prenom", sanitize_text_field($_POST["prenom"]));
        $title.=sanitize_text_field($_POST["prenom"]);
    }
    if ( isset( $_REQUEST['num_poste'] ) ) {
        update_post_meta($post->ID, "num_poste", sanitize_text_field($_POST["num_poste"]));
    }
    
    if ( isset($post)){
        $args = array(
            'ID'           => $post->ID,
            'post_title'   => $title,
        );
        remove_action('save_post', 'save_details');
        wp_update_post( $args );
        add_action('save_post', 'save_details');
    }
}

add_action('save_post', 'save_details');

add_action( 'init', 'wpm_add_taxonomies', 0 );


function wpm_add_taxonomies() {

	$labels_techno = array(
		'name'              			=> _x( 'Technos', 'taxonomy general name'),
		'singular_name'     			=> _x( 'Techno', 'taxonomy singular name'),
		'search_items'      			=> __( 'Chercher un Techno'),
		'all_items'        				=> __( 'Toutes les Technos'),
		'edit_item'         			=> __( 'Editer le Techno'),
		'update_item'       			=> __( 'Mettre à jour le Techno'),
		'add_new_item'     				=> __( 'Ajouter un nouveau Techno'),
		'new_item_name'     			=> __( 'Valeur du Techno'),
		'separate_items_with_commas'	=> __( 'Séparer les Technos avec une virgule'),
		'menu_name'         => __( 'Technos'),
	);

	$args_techno = array(

	// SI 'hierarchical' est défini à FALSE, la taxonomie se comportera comme une étiquette standard	// Détermine si l'objet de taxonomie est hiérarchique.
		'hierarchical'      => true,
		'labels'            => $labels_techno,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'technos' ),
	);

	register_taxonomy( 'technos', 'Coop', $args_techno );

	
	$labels_Secteur_Dactivité = array(
		'name'                       => _x( 'Secteur_Dactivités', 'taxonomy general name'),
		'singular_name'              => _x( 'Secteur_Dactivité', 'taxonomy singular name'),
		'search_items'               => __( 'Rechercher un Secteur_Dactivité'),
		'popular_items'              => __( 'Secteur_Dactivité populaires'),
		'all_items'                  => __( 'Tous les Secteur_Dactivités'),
		'edit_item'                  => __( 'Editer un Secteur_Dactivité'),
		'update_item'                => __( 'Mettre à jour un Secteur_Dactivité'),
		'add_new_item'               => __( 'Ajouter un nouveau Secteur_Dactivité'),
		'new_item_name'              => __( 'Nom du nouveau Secteur_Dactivité'),
		'separate_items_with_commas' => __( 'Séparer les Secteur_Dactivités avec une virgule'),
		'add_or_remove_items'        => __( 'Ajouter ou supprimer un Secteur_Dactivité'),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisés'),
		'not_found'                  => __( 'Pas de Secteur_Dactivités trouvés'),
		'menu_name'                  => __( 'Secteur_Dactivités'),
	);

	$args_Secteur_Dactivité = array(
		'hierarchical'          => true,
		'labels'                => $labels_Secteur_Dactivité,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'Secteur_Dactivités' ),
	);

	register_taxonomy( 'Secteur_Dactivités', 'Coop', $args_Secteur_Dactivité );
	

	$labels_bureau = array(
		'name'                       => _x( 'Bureaux', 'taxonomy general name'),
		'singular_name'              => _x( 'Bureau', 'taxonomy singular name'),
		'search_items'               => __( 'Rechercher un bureau'),
		'popular_items'              => __( 'Bureaux populaires'),
		'all_items'                  => __( 'Toutes les bureaux'),
		'edit_item'                  => __( 'Editer une bureau'),
		'update_item'                => __( 'Mettre à jour un bureau'),
		'add_new_item'               => __( 'Ajouter une nouveau bureau'),
		'new_item_name'              => __( 'Nom du nouveau bureau'),
		'add_or_remove_items'        => __( 'Ajouter ou supprimer un bureau'),
		'choose_from_most_used'      => __( 'Choisir parmi les bureaux les plus utilisées'),
		'not_found'                  => __( 'Pas de bureaux trouvés'),
		'menu_name'                  => __( 'Bureaux'),
	);

	$args_bureau = array(
	// Si 'hierarchical' est défini à true, notre taxonomie se comportera comme une catégorie standard
		'hierarchical'          => false,
		'labels'                => $labels_bureau,
		'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'bureaux' ),
	);

    register_taxonomy( 'bureaux', 'Coop', $args_bureau );

    $labels_batiment = array(
		'name'                       => _x( 'Bâtiments', 'taxonomy general name'),
		'singular_name'              => _x( 'Bâtiment', 'taxonomy singular name'),
		'search_items'               => __( 'Rechercher un bâtiment'),
		'popular_items'              => __( 'Bâtiment populaires'),
		'all_items'                  => __( 'Toutes les bâtiments'),
		'edit_item'                  => __( 'Editer une bâtiment'),
		'update_item'                => __( 'Mettre à jour un bâtiment'),
		'add_new_item'               => __( 'Ajouter une nouveau bâtiment'),
		'new_item_name'              => __( 'Nom du nouveau bâtiment'),
		'add_or_remove_items'        => __( 'Ajouter ou supprimer un bâtiment'),
		'choose_from_most_used'      => __( 'Choisir parmi les bâtiments les plus utilisées'),
		'not_found'                  => __( 'Pas de bâtiments trouvés'),
		'menu_name'                  => __( 'Bâtiments'),
	);

	$args_batiment = array(
	// Si 'hierarchical' est défini à true, notre taxonomie se comportera comme une catégorie standard
		'hierarchical'          => false,
		'labels'                => $labels_batiment,
		'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'batiments' ),
	);

    register_taxonomy( 'batiments', 'Coop', $args_batiment );








	
}