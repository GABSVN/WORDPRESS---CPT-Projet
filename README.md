

<!-- BANNIERE Wordpress CPT Project -->
<!------------------------------------------------------- -->

<p align="center">
  <a href="https://user-images.githubusercontent.com/99598124/178593393-6cb0f34e-dfe4-45ab-8d68-efb13a6abdba.gif"><img src="![CPT Projet-logo-A](https://user-images.githubusercontent.com/99598124/178590971-e022aa15-0116-404c-a6b7-56d4e7fa0615.gif)" alt="my banner"></a>
</p>

<!-- --------------------------------------------------- -->

<!-- ------------------Titre---------------------------------- -->
<h3 align="center">
Salut, je suis <a href="https://www.gabsvn.ch/" target="_blank" rel="noreferrer">GABSVN</a> 👋
</h3>
<!-- ------------------------------------------------------------------------>

<!-- -------Badges Wordpress et PHP license 7 - 8 -------------------------------------- -->

<div align="center">
  <br /><br />
![](https://img.shields.io/badge/Cms-Wordpress-informational?style=flat&logo=Wordpress&color=336791)
[![PHP 7 and 8](https://img.shields.io/badge/php-7%20/%208-blue.svg?style=flat-square)](https://wordpress.org/support/update-php/)
  <br /><br /><br />
</div>
<!------------------------------------------------------- -->


<h2 align="center">
CPT Projet
</h2> 

Le projet CPT est un outils qui fournit des fonctionnalités étendues aux types de publication personnalisées et aux taxonomies WordPress. Il permet aux développeurs de créer rapidement des types de publication et des taxonomies sans devoir écrire le même code encore et encore.

Les CPT étendus fonctionnent à la fois avec l'éditeur classique et l'éditeur de blocs.

<h2 align="center">
Paramètres par défaut améliorés pour les types de publication
</h2> 

## Fonctionnalités d'administration ##

 * Types de publication et taxonomies automatiquement ajoutés à la section "En bref" du tableau de bord
 * Création déclarative des colonnes du tableau sur l'écran de listage des types de publication :
   * Colonnes pour la méta post, les termes de taxonomie, les images en vedette, les champs de publication, les connexions Posts 2 Posts et les fonctions personnalisées
   * Colonnes triables pour les méta-postes, les termes de taxonomie et les champs de publication
   * Restrictions de capacité utilisateur
   * Colonne de tri et ordre de tri par défaut
 * Plusieurs méta-boîtes personnalisées disponibles pour les taxonomies sur l'écran de post-édition :
   * Liste simplifiée des cases à cocher
   * Boutons radio
   * Menu déroulant
   * Fonction personnalisée  
 * Types de publication éventuellement ajoutés à la section "Récemment publié" sur le tableau de bord
 * Création déclarative de colonnes de tableau sur l'écran de listage des termes de taxonomie :
   * Colonnes pour les méta-termes et les fonctions personnalisées
   * Restrictions de capacité utilisateur
 * Contrôles de filtrage sur l'écran de liste des types de publication pour activer le filtrage des publications par méta de publication, termes de taxonomie, auteur de publication et dates de publication
 * Remplacer le texte "Image en vedette" et "Entrez le titre ici"
 
 

## Fonctionnalités front-End pour les types de publication ##

 * Remplacer les variables de requête publiques ou privées par défaut telles que `posts_per_page`, `orderby`, `orderetnopaging`
 * Ajouter le type de publication au flux RSS principal du site
  * Spécifiez les variables de requête publiques qui activent le filtrage par méta post et dates de publication
 * Spécifiez une structure de permaliens personnalisée :
   * Par exemple `reviews/%year%/%month%/%review%`
   * Prend en charge toutes les balises de réécriture pertinentes, y compris les dates et les taxonomies personnalisées
   * Intégration automatique avec le plugin Rewrite Rule Testing
 * Spécifiez les variables de requête publiques qui permettent le tri par post-méta, termes de taxonomie et champs de publication

## Exigences minimales ##

* **PHP:** 7.4  
  - PHP 8.0 et 8.1 sont pris en charge
* **WordPress:** 5.6  
  - Testé jusqu'à WP 6.0

Les CPT étendus devraient fonctionner avec les versions de WordPress jusqu'à 4.9, mais ces versions ne sont pas testées et ne sont pas officiellement prises en charge.

## Installation ##

Extended CPTs est un plugin, ce qui signifie que vous pouvez l'inclure dans votre projet. Installez-le à l'aide de Composer :

```bash
Docker compose
```

Les moyens d'installation ou d'utilisation, ne sont pas officiellement pris en charge et se font à vos risques et périls.

## Usage ##

Besoin d'un type de message simple sans fioritures? Vous pouvez enregistrer un type de publication avec un seul paramètre :

```php
add_action( 'init', function() {
	register_extended_post_type( 'article' );
} );
```

Et vous pouvez enregistrer une taxonomie avec seulement deux paramètres :

```php
add_action( 'init', function() {
	register_extended_taxonomy( 'location', 'article' );
} );
```

Essayez-le. Vous aurez un type de publication public hiérarchique avec une interface utilisateur d'administration, une taxonomie publique hiérarchique avec une interface utilisateur d'administration, et toutes les étiquettes et les messages mis à jour pour eux seront automatiquement générés.

Ou pour un peu plus de fonctionnalité :

```php
add_action( 'init', function() {
	register_extended_post_type( 'story', [

		# Add the post type to the site's main RSS feed:
		'show_in_feed' => true,

		# Show all posts on the post type archive:
		'archive' => [
			'nopaging' => true,
		],

		# Add some custom columns to the admin screen:
		'admin_cols' => [
			'story_featured_image' => [
				'title'          => 'Illustration',
				'featured_image' => 'thumbnail'
			],
			'story_published' => [
				'title_icon'  => 'dashicons-calendar-alt',
				'meta_key'    => 'published_date',
				'date_format' => 'd/m/Y'
			],
			'story_genre' => [
				'taxonomy' => 'genre'
			],
		],

		# Add some dropdown filters to the admin screen:
		'admin_filters' => [
			'story_genre' => [
				'taxonomy' => 'genre'
			],
			'story_rating' => [
				'meta_key' => 'star_rating',
			],
		],

	], [

		# Override the base names used for labels:
		'singular' => 'Story',
		'plural'   => 'Stories',
		'slug'     => 'stories',

	] );

	register_extended_taxonomy( 'genre', 'story', [

		# Use radio buttons in the meta box for this taxonomy on the post editing screen:
		'meta_box' => 'radio',

		# Add a custom column to the admin screen:
		'admin_cols' => [
			'updated' => [
				'title_cb'    => function() {
					return '<em>Last</em> Updated';
				},
				'meta_key'    => 'updated_date',
				'date_format' => 'd/m/Y'
			],
		],

	] );
} );
```

Boum, maintenant nous avons:

* Un type de publication "Stories", avec des étiquettes correctement générées et des messages mis à jour, trois colonnes personnalisées dans la zone d'administration (dont deux peuvent être triées), des histoires ajoutées au flux RSS principal et toutes les histoires affichées sur l'archive de type de publication.
* Une taxonomie 'Genre' attachée au type de publication 'Stories', avec des étiquettes correctement générées et des messages de mise à jour des termes, et une colonne personnalisée dans la zone d'administration.

Les fonctions `register_extended_post_type()` et `register_extended_taxonomy()` fsont finalement des enveloppes pour les fonctions `register_post_type()` et `register_taxonomy()` dans le noyau de WordPress, de sorte que n'importe lequel des paramètres de ces fonctions peut être utilisé.

Bien entendu, vous pouvez faire beaucoup plus.

## Contribuez et testez! ##

Veuillez contribuer et tester.

## License: GPLv2 ou ultérieure ##

Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier selon les termes de la licence publique générale GNU telle que publiée par la Free Software Foundation ; soit la version 2 de la licence, soit (à votre choix) toute version ultérieure.

Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE GARANTIE ; sans même la garantie implicite de QUALITÉ MARCHANDE ou d'ADÉQUATION À UN USAGE PARTICULIER. Voir la licence publique générale GNU pour plus de détails.



<p align="center">
  <a href="https://www.gabsvn.ch/" target="_blank" rel="noreferrer"><img src="https://user-images.githubusercontent.com/99598124/177351635-51da0f6b-bd80-461d-bb3c-513397d6137d.gif" alt="my banner"></a>
</p>





<!-- ---------------------------------------------------------->





