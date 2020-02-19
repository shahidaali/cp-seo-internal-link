<div class="wrap">
	<h1><?php _e('Seo Internal Link Settings'); ?></h1>

	<?php if( !empty($messages) ): ?>
		<div id="setting-error-settings_updated" class="notice notice-<?php echo $messages['status']; ?> settings-error is-dismissible"> 
			<p><strong><?php echo $messages['message']; ?></strong></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.') ?></span></button>
		</div>
	<?php endif; ?>

	<form action="" method="post">
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php _e('Plugin Status'); ?></th>
					<td><label for="plugin_enabled">
					<input name="plugin_enabled" type="checkbox" id="plugin_enabled" value="1" <?php echo $this->is_checked( $this->get_setting('plugin_enabled', 1), 1 ); ?>><?php _e('Uncheck this checkbox to disable plugin functionality.') ?></label></td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Show Links in Excerpts'); ?></th>
					<td><label for="show_in_excerpt">
					<input name="show_in_excerpt" type="checkbox" id="show_in_excerpt" value="1" <?php echo $this->is_checked( $this->get_setting('show_in_excerpt', 1), 1 ); ?>><?php _e('Uncheck this checkbox to disable links for excerpts.') ?></label></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
	    	<input type="hidden" name="cp_seo_internal_link_settings" value="1" />
	    	<input type="submit" class="button-primary" value="<?php _e('Submit'); ?>"/>
		</p>
	</form>
</div>
