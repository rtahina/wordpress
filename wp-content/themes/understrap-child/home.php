<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use RTRealEstate\Controllers\PropertyController;
use RTRealEstate\Controllers\CityController;

$property_controller = new PropertyController();
$city_controller = new CityController();
$property_data = $property_controller->archive( 'ID', 'DESC', 4 );
$city_data = $city_controller->archive( 'ID', 'DESC', 4 );

get_header();

$container = get_theme_mod( 'understrap_container_type' );

?>

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">
				<section id="properties">
					<h2>Latest properties</h2>
					<div class="container">
						<div class="row">
							<?php foreach( $property_data['properties'] as $property ) : ?>
								<div class="col-3">
									<div class="property-card">
										<h3><a href="<?php echo $property['permalink'] ?>"><?php echo $property['name'] ?></a></h3>
										<div class="types"><?php echo $property['types'] ?></div>
										<div class="city-card-image">
											<a href="<?php echo $property['permalink'] ?>"><?php echo $property['image']; ?></a>
										</div>
										<div class="city-features">
											<div class="cost"><?php echo $property['cost'] ?></div>
											<div class="address"><?php echo $property['address'] ?></div>
											<div class="floor"><u>Floor:</u> <?php echo $property['floor'] ?></div>
											<div class="city">
												<?php if( $property['city'] ): ?>
													In <?php echo $property['city']->post_title ?>
												<?php endif; ?>
											</div>
											<div class="link">
												<a href="<?php echo $property['permalink'] ?>">See details</a>
											</div>
										</div> <!-- /city-features -->
									</div> <!-- /city-card -->
								</div>
							<?php endforeach; ?>
						</div>
					</div> <!-- .container -->
				</section>
				<section id="cities">
					<h2>Latest cities</h2>
					<div class="container">
						<div class="row">
							<?php foreach( $city_data['cities'] as $city ) : ?>
								<div class="col-3">
									<div class="city-card">
										<h3><a href="<?php echo $city['permalink'] ?>"><?php echo $city['name'] ?></a></h3>
										<div class="city-card-image">
											<a href="<?php echo $city['permalink'] ?>"><?php echo $city['image']; ?></a>
										</div>
										<div class="link">
											<a href="<?php echo $property['permalink'] ?>">See properties in <?php echo $city['name'] ?></a>
										</div>
									</div> <!-- /city-card -->
								</div>
							<?php endforeach; ?>
						</div>
					</div> <!-- .container -->
				</section>

				<section id="add_property">
					<h3>Add a property</h3>
					<?php get_template_part( 'templates/add-property' ); ?>
				</section>
			</main><!-- #main -->

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #page-wrapper -->

<?php
get_footer();
