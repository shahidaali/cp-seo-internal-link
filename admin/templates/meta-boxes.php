<div class="cp-seo-internal-link-meta">
	<div class="cp-seo-internal-link-meta-group">
		<!-- Meta Field -->
		<div class="form-field" style="margin-bottom: 10px;">
			<label for="cp_seo_internal_link_keyword" style="display: block;"><?php _e( 'Keyword', 'cp_seo_internal_link' ) ?></label>
			<input type="text" class="cp_seo_internal_link_keyword" name="cp_seo_internal_link_keyword" value="<?php echo esc_attr( $this->get_data( $saved_meta, 'keyword' ) ); ?>" style="width: 100%; max-width: 100%;" placeholder="<?php _e( 'Enter Keyword', 'cp_seo_internal_link' ) ?>">
		</div><!-- #Meta Field -->

		<!-- Meta Field -->
		<div class="form-field" style="margin-bottom: 10px;">
			<label for="cp_seo_internal_link_object_type" style="display: block;"><?php _e( 'Filter Url By', 'cp_seo_internal_link' ) ?></label>
			
			<select class="cp_seo_internal_link_object_type" name="cp_seo_internal_link_object_type" style="width: 100%; max-width: 100%;">

				<?php foreach ($object_types as $group => $objects) { ?>

					<optgroup label="<?php echo $group; ?>">
						<?php echo $this->select_options( $objects, $this->get_data( $saved_meta, 'object_type' ) ); ?>
					</optgroup>

				<?php } ?>

			</select>
		</div><!-- #Meta Field -->

		<!-- Meta Field -->
		<div class="form-field form-field-select-url" style="margin-bottom: 10px;">
			<label for="cp_seo_internal_link_object_id" style="display: block;"><?php _e( 'Select Url', 'cp_seo_internal_link' ) ?></label>
			
			<select class="cp_seo_internal_link_object_id" name="cp_seo_internal_link_object_id" style="width: 100%; max-width: 100%;">

			</select>
		</div><!-- #Meta Field -->

		<!-- Meta Field -->
		<div class="form-field form-field-custom-url" style="margin-bottom: 10px; display: none;">
			<label for="cp_seo_internal_link_custom_url" style="display: block;"><?php _e( 'Custom Url', 'cp_seo_internal_link' ) ?></label>
			<input type="text" class="cp_seo_internal_link_custom_url" name="cp_seo_internal_link_custom_url" value="<?php echo esc_attr( $this->get_data( $saved_meta, 'custom_url' ) ); ?>" style="width: 100%; max-width: 100%;" placeholder="<?php _e( 'Enter Custom Url', 'cp_seo_internal_link' ) ?>">
		</div><!-- #Meta Field -->

		<!-- Selected Url -->
		<input type="hidden" name="cp_seo_internal_link_selected" class="cp_seo_internal_link_selected" value="<?php echo $this->get_data( $saved_meta, 'object_id' ); ?>">
	</div>
</div>