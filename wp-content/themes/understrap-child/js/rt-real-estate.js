(function ($) {
	'use strict';

	$(function () {

        /* registration */
        $('body').on('submit', '#form_add_property', function (e) {
			e.preventDefault();

			$('#submitButton').prop('disabled', true);
			$('#submitButton').html('Adding a property...');

            var nonce = $('#add-property-nonce').val();
            var types = [];
            $('input[name="propertyType"]:checked').each(function() {
                types.push(this.value);
             });

            $.ajax({
				type: "post",
				dataType: "json",
				url: ajax_object.ajax_url,
				data: {
					action: "add_property",
					nonce: nonce,
					name: $('#propertyName').val(),
					description: $('#propertyDescription').val(),
					cost: $('#propertyCost').val(),
					address: $('#propertyAddress').val(),
					floor: $('#propertyFloor').val(),
                    type: JSON.stringify(types),
                    city: $('#propertyCity').val(),
				},
				success: function (response) {
					if (response.success === false) {
                        $('#submitErrorMessage').html('<div class="text-center text-danger mb-3">' + response.message + '</div>');
                    } else {
						$('#submitErrorMessage').html('<div class="text-center text-success mb-3">Property added</div>');
					}
				},
				complete: function (response) {
					$('#submitButton').prop('disabled', false);
					$('#submitButton').html('Submit');
				}
			});
		});
	});
})(jQuery);