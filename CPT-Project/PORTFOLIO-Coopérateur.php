<?php


/**
 * @package 	Coopérateur
 */
/*

Plugin Name: 	PORTFOLIO-Coopérateur-Prototype
Plugin URI: 	http://github.com/GABSVN
Description: 	Si ce portofolio CPT tu utiliseras, plus de travail tu obtiendras! Que la force soit avec toi jeune padawan.. Au travail!!
Version: 		32000.0
Author: 		Gabriel_FDS
Author URI: 	http://github.com/GABSVN
Textdomain: 	GABSVN
License: 		GPLv2

*/



//Pour s'assurer qu'aucune infos ne soit accessible, suite à un appel
if ( !function_exists( 'add_action' ) ) {
	echo 'Tu m\'as appelé ? Ben non, je suis occupé.';
	exit;
}

//Définir la constante globale menant au répertoir du plugin
define( 'COOP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

//Utile si on veut lancer des fonctions à l'activation ou désactivation du plugin
/*register_activation_hook( __FILE__, array( 'Coopérateur', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Coopérateur', 'plugin_deactivation' ) );*/

//Pour ajouter un CSS au plugin et BOOTSTRAP
function add_scripts_styles() {

	//CSS
	wp_register_style('main-Coop-style', plugins_url( 'style.css', __FILE__ ), array(), true);
	wp_enqueue_style('main-Coop-style',1);

	//BOOTSTRAP
	wp_register_style('bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', array(), true);
	wp_enqueue_style('bootstrap-style',1);

	wp_register_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js','',false,true);
	wp_enqueue_script( 'bootstrap-js');
	
}
//Lance la fonction ci-dessus au hook wp_enqueue_scripts
add_action( 'wp_enqueue_scripts', 'add_scripts_styles' );

//On appel nos autres fichiers php
require_once( COOP__PLUGIN_DIR . 'Coopérateur-cpt.php' );
require_once( COOP__PLUGIN_DIR . 'Coopérateur-shortcode.php' );



