jQuery(document).ready(function($){
	// Change select object type
	$(document).on('change', '#cp_seo_internal_link_object_type', function(e) { 
        e.preventDefault();

        // If custom link selected show custom link field
        if( $(this).val() == 'custom_link' ) {
        	$('.form-field-select-url').hide();
        	$('.form-field-custom-url').show();
        	return false;
        } else {
        	$('.form-field-select-url').show();
        	$('.form-field-custom-url').hide();
        }

        // Fetch url options for selected object
        $.ajax({
            url : cp_seo_internal_link.ajax_url,
            type : 'post',
            dataType: "json",
            data : {
                action : 'cp_seo_internal_link_ajax',
                object_type : $(this).val(),
                selected_id : $('#cp_seo_internal_link_selected').val(),
                security : cp_seo_internal_link.check_nonce
            },
            success : function( response ) {
                console.log(response);
                $('#cp_seo_internal_link_object_id').html( response.options );
            }
        });
    })

    $('#cp_seo_internal_link_object_type').change(); 
});