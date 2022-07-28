(function ($) {
	'use strict';

	$(function () {

        /* registration */
        $('body').on('submit', '#form_add_property', function (e) {
			e.preventDefault();

			$('#submitButton').prop('disabled', true);
			$('#submitButton').html('Adding a property...');

			var formData = new FormData(this);

			var types = [];
            $('input[name="propertyType"]:checked').each(function() {
				types.push(this.value);
			});
			formData.append( 'propertyTypes', types);
			formData.append( 'action', 'add_property');
			
            $.ajax({
				type: "post",
				dataType: "json",
				url: ajax_object.ajax_url,
				data: formData,
				contentType: false,
				processData: false,
				error: function(xhr, status, error) {
					var err = JSON.parse(xhr.responseText);
					console.log(err.Message);
				},
				success: function (response) {
					if (response.success === false) {
                        $('#submitErrorMessage').html('<div class="text-center text-danger mb-3">' + response.message + '</div>');
                    } else {
						$('#submitErrorMessage').html('<div class="text-center text-success mb-3">Property added</div>');
					}
					$('#submitButton').prop('disabled', false);
					$('#submitButton').html('Submit');
				},
				complete: function (response) {
					$('#submitButton').prop('disabled', false);
					$('#submitButton').html('Submit');
				}
			});
		});
	});
})(jQuery);