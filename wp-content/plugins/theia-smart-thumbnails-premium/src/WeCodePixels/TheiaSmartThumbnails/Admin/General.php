<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Admin;

use WeCodePixels\TheiaSmartThumbnails\Misc;
use WeCodePixels\TheiaSmartThumbnails\Options;

class General {
	public function echoPage() {
		Misc::echo_regenerate_all_thumbnails_notice();
		?>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'tst_options_general' );
			$options = get_option( 'tst_general' );
			$options = is_array( $options ) ? $options : array();
			?>
			<table class="form-table">
				<tr>
					<th>
						<?php _e( "For portrait photos, place the focus point on the upper side:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<label>
							<input type="hidden" name="tst_general[portraitUpperByDefault]" value="false">
							<input type="checkbox"
							       value="true"
							       name="tst_general[portraitUpperByDefault]"<?php echo $options['portraitUpperByDefault'] ? ' checked' : '' ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( "Useful for people portraits, fashion photography, etc. so the heads aren't cut off. This can be overwritten by choosing a custom focus point for each image.", 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th>
						<?php _e( "Default focus point for all photos:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<select id="tst_general_default_focal_point" name="tst_general[default_focal_point]">
							<?php
							foreach ( Options::get_default_focal_points() as $key => $value ) {
								$output = '<option value="' . $key . '"' . ( $key == $options['default_focal_point'] ? ' selected' : '' ) . '>' . $value[0] . '</option>' . "\n";
								echo $output;
							}
							?>
						</select>

						<div class="theiaSmartThumbnails_defaultPosition"
							<?php
							if ( $options['default_focal_point'] != 'custom' ) {
								echo 'style="display: none"';
							}
							?>
						>
							<input type="hidden"
							       class="small-text"
							       id="tst_default_focal_point_x"
							       name="tst_general[default_focal_point_x]"
							       value="<?php echo htmlentities( $options['default_focal_point_x'] ); ?>">

							<input type="hidden"
							       class="small-text"
							       id="tst_default_focal_point_y"
							       name="tst_general[default_focal_point_y]"
							       value="<?php echo htmlentities( $options['default_focal_point_y'] ); ?>">

							<div class="theiaSmartThumbnails_mediaUpload">
								<div id="focus_point_picker" class="_picker">
									<img src="<?php echo plugins_url( '/assets/images/example-for-picker.jpg', THEIA_SMART_THUMBNAILS_MAIN ); ?>">
								</div>
							</div>
						</div>
					</td>
				</tr>


				<tr>
					<th>
						<?php _e( "Enlarge smaller images:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<label>
							<input type="hidden" name="tst_general[enlargeSmallImages]" value="false">
							<input type="checkbox"
							       value="true"
							       name="tst_general[enlargeSmallImages]"<?php echo $options['enlargeSmallImages'] ? ' checked' : '' ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( 'Unless enabled, images that are smaller than the thumbnail size will not be enlarged.', 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
			</table>

			<?php
			$sizes       = Misc::get_image_sizes();
			$theme_sizes = array();
			foreach ( $sizes as $size ) {
				$theme_sizes[] = $size['width'] . 'x' . $size['height'];
			}
			$theme_sizes = implode( "\n", $theme_sizes );
			$options     = get_option( 'tst_general' );
			$options     = is_array( $options ) ? $options : array();

			$disabled_name_prefix = THEIA_SMART_THUMBNAILS_IS_PRO ? '' : 'disabled_';
			$disabled_if_free     = THEIA_SMART_THUMBNAILS_IS_PRO ? '' : 'disabled';
			?>

			<hr>
			<table class="form-table">
				<tr>
					<th>
						<?php _e( "Cache busting:", 'theia-smart-thumbnails' ); ?>
						<?php echo Misc::get_pro_only_notice(); ?>
					</th>
					<td>
						<label>
							<input type="hidden"
							       name="<?php echo $disabled_name_prefix; ?>tst_general[cacheBusting]"
							       value="false">
							<input type="checkbox"
							       value="true"
							       name="<?php echo $disabled_name_prefix; ?>tst_general[cacheBusting]"
								<?php echo $options['cacheBusting'] && THEIA_SMART_THUMBNAILS_IS_PRO ? ' checked' : ''; ?>
								<?php echo $disabled_if_free; ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( 'Force cache-clearing after changing the focus point by appending a query variable (e.g. "image.jpg?theia_smart_thumbnails_file_version=2"). Works for both visitors and CDNs.', 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th>
						<label for="tst_previewSizes"><?php _e( "Thumbnail preview sizes:", 'theia-smart-thumbnails' ); ?></label>
						<?php echo Misc::get_pro_only_notice(); ?>
					</th>
					<td>
						<label>
							<?php $value = $options['previewSizes']; ?>
							<textarea id="tst_previewSizes"
							          name="<?php echo $disabled_name_prefix; ?>tst_general[previewSizes]"
							          class="tst_previewSizes"
								<?php echo $disabled_if_free; ?>><?php echo $value; ?></textarea>
						</label>

						<br>

						<input type="button"
						       class="button"
						       value="Load theme sizes"
							<?php echo $disabled_if_free; ?>
							   onclick='jQuery("#tst_previewSizes").val(<?php echo json_encode( $theme_sizes ); ?>)'>

						<input type="button"
						       class="button"
						       value="Load default sizes"
							<?php echo $disabled_if_free; ?>
							   onclick='jQuery("#tst_previewSizes").val(<?php echo json_encode( Options::get_default_preview_sizes() ); ?>)'>

						<p class="description">
							<?php _e( 'These are only used as a preview when choosing the focus point. Enter one size per line. Example: 400x300.', 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
			</table>

			<hr>

			<h3>Troubleshooting</h3>
			<table class="form-table">
				<tr>
					<th>
						<?php _e( "Enable front-end compatibility:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<label>
							<input type="hidden" name="tst_general[enableInFrontEnd]" value="false">
							<input type="checkbox"
							       value="true"
							       name="tst_general[enableInFrontEnd]"<?php echo $options['enableInFrontEnd'] ? ' checked' : '' ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( "This greatly improves compatibility with the <strong>Enfold theme</strong>, <strong>Masonry grids</strong>, <strong>Avia Layout Builder</strong>, and virtually any layout that's using <strong>'background-size: cover'</strong>.", 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th>
						<?php _e( "Allow other compatible plugins to use Theia Smart Thumbnails:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<label>
							<input type="hidden" name="tst_general[allowOtherPlugins]" value="false">
							<input type="checkbox"
							       value="true"
							       name="tst_general[allowOtherPlugins]"<?php echo $options['allowOtherPlugins'] ? ' checked' : '' ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( "Enable this in order to integrate with MetaSlider and possibly other plugins. The next option may also need to be enabled. Otherwise, thumbnails may not be updated properly.", 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th>
						<?php _e( "Delete previous undefined thumbnails when changing the focus point:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<label>
							<input type="hidden" name="tst_general[allowThumbsReplacing]" value="false">
							<input type="checkbox"
							       value="true"
							       name="tst_general[allowThumbsReplacing]"<?php echo $options['allowThumbsReplacing'] ? ' checked' : '' ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( "Useful if your theme is using Aqua-Resizer or other custom resizing methods. Standard WordPress thumbnails defined using add_image_size are always regenerated whether this option is enabled or not. This option also removes all other additional thumbnails with similar filenames.", 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th>
						<?php _e( "Process undefined image sizes:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<label>
							<input type="hidden" name="tst_general[enableForUndefinedImageSizes]" value="false">
							<input type="checkbox"
							       value="true"
							       name="tst_general[enableForUndefinedImageSizes]"<?php echo $options['enableForUndefinedImageSizes'] ? ' checked' : '' ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( "Useful if your theme is using Aqua-Resizer or other custom resizing methods. If disabled, only thumbnails sizes declared using add_image_size will be processed.", 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th>
						<?php _e( "Find image IDs from image filenames:", 'theia-smart-thumbnails' ); ?>
					</th>
					<td>
						<label>
							<input type="hidden" name="tst_general[findImageIdFromImageFile]" value="false">
							<input type="checkbox"
							       value="true"
							       name="tst_general[findImageIdFromImageFile]"<?php echo $options['findImageIdFromImageFile'] ? ' checked' : '' ?>>
							<?php _e( 'Enable', 'theia-smart-thumbnails' ); ?>
						</label>

						<p class="description">
							<?php _e( "Useful if your theme is using Aqua-Resizer or other custom resizing methods. If the resizer does not receive the image ID, it can try and find it by using the image filename.", 'theia-smart-thumbnails' ); ?>
						</p>
					</td>
				</tr>
			</table>

			<hr>

			<input type="submit"
			       class="button-primary"
			       value="<?php _e( 'Save All Changes', 'theia-smart-thumbnails' ) ?>" />


		</form>

		<script>
			var tstPicker;

			jQuery(document).ready(function () {
				tstPicker = new tst.createPicker({
					image: "#focus_point_picker",
					input: "#tst_default_focal_point_x, #tst_default_focal_point_y",
					position: {
						x: <?php echo $options['default_focal_point_x']; ?>,
						y: <?php echo $options['default_focal_point_y']; ?>
					}
				});

				var f = function () {
					if (jQuery('#tst_general_default_focal_point').val() == 'custom') {
						jQuery('.theiaSmartThumbnails_defaultPosition').show();
						tstPicker.refreshPreview();
					}
					else {
						jQuery('.theiaSmartThumbnails_defaultPosition').hide();
					}
				};

				jQuery('#tst_general_default_focal_point').change(f);

				f();
			});
		</script>
		<?php
	}
}
