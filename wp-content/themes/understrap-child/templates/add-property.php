<?php
use RTRealEstate\Controllers\CityController;
use RTRealEstate\Helpers\TaxonomyHelper;

$city_controller = new CityController();
$cities = $city_controller->archive( 'title', 'ASC', -1 );

$types = TaxonomyHelper::get_taxonomy_list( 'property-type' );
?>
<div class="container col-6">
    <form id="form_add_property" enctype="multipart/form-data">
        <div class="" id="submitErrorMessage">
            
        </div>
        <div class="mb-3">
            <label class="form-label" for="propertyName">Property name*</label>
            <input class="form-control" id="propertyName" type="text" placeholder="Property name" data-sb-validations="required" />
            <div class="invalid-feedback" data-sb-feedback="propertyName:required">Property name is required.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="propertyDescription">Property description</label>
            <textarea class="form-control" id="propertyDescription" type="text" placeholder="Property description" style="height: 10rem;" data-sb-validations="required"></textarea>
            <div class="invalid-feedback" data-sb-feedback="propertyDescription:required">Property description is required.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="propertyCost">Cost</label>
            <input class="form-control" id="propertyCost" type="text" placeholder="Cost" data-sb-validations="required" />
            <div class="invalid-feedback" data-sb-feedback="cost:required">Cost is required.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="propertyAddress">Address</label>
            <input class="form-control" id="propertyAddress" type="text" placeholder="Address" data-sb-validations="required" />
            <div class="invalid-feedback" data-sb-feedback="address:required">Address is required.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="propertyFloor">Floor</label>
            <input class="form-control" id="propertyFloor" type="text" placeholder="Floor" data-sb-validations="required" />
            <div class="invalid-feedback" data-sb-feedback="floor:required">Floor is required.</div>
        </div>
        <?php if ( $types ) : ?>
            <div class="mb-3">
                <label class="form-label d-block">Type</label>
                <?php foreach( $types as $type ) { ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input type-options" name="propertyType" value="<?php echo $type->term_id ?>" type="checkbox" name="type" data-sb-validations="" />
                        <label class="form-check-label" for="optionA"><?php echo $type->name ?></label>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label class="form-label" for="propertyCity">City</label>
            <select class="form-select" id="propertyCity" aria-label="City">
                <?php foreach ( $cities['cities'] as $city ) : ?>
                    <option value="<?php echo $city['ID']; ?>"><?php echo $city['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <span class="text-danger">(*) required fields</span>
        <div class="mb-3">
        <?php wp_nonce_field('add-property', 'add-property-nonce');?>
        <div class="d-grid">
            <button class="btn btn-primary btn-lg" id="submitButton" type="submit">Submit</button>
        </div>
    </form>
</div>
<!-- <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script> -->