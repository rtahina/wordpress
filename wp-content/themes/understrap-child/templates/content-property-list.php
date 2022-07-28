<?php
/**
 * List properties of a given city
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use RTRealEstate\Controllers\PropertyController;

$property_controller = new PropertyController();
$property = $property_controller->single();
?>

<div class="city-features">
    <div class="types"><u>Type:</u> <?php echo $property['types'] ?></div>
    <div class="cost"><u>Cost:</u> <?php echo $property['cost'] ?></div>
    <div class="address"><u>Address:</u> <?php echo $property['address'] ?></div>
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
