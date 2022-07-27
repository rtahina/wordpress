(function ($) {
	'use strict';

	$(function () {

        /* registration */
        $('body').on('submit', '#form_add_property', function (e) {
			e.preventDefault();

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
                        //
                    }
				},
				complete: function (response) {
					//
				}
			});
		});
	});
})(jQuery);