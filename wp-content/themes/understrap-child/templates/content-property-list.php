<?php
/**
 * List properties of a given city
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use RTRealEstate\Controllers\PropertyController;
use RTRealEstate\Helpers\PropertyHelper;

$properties = PropertyHelper::get_property_list_of_single_city();
?>

<div class="row">
    <h4>Properties in <?php the_title() ?></h4>
    <?php foreach( $properties as $property ) : ?>
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
</div> <!-- /city-features -->
