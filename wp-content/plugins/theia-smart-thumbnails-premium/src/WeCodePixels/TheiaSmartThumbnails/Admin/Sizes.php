<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Admin;

use WeCodePixels\TheiaSmartThumbnails\Misc;
use WeCodePixels\TheiaSmartThumbnails\Options;

class Sizes {
    public function echoPage() {
        $sizes            = Misc::get_image_sizes();
        $images_url       = plugin_dir_url( THEIA_SMART_THUMBNAILS_MAIN ) . '/assets/images/';
        $disabled_if_free = THEIA_SMART_THUMBNAILS_IS_PRO ? '' : 'disabled';

        ?>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'tst_options_sizes' );
            ?>

            <?php Misc::echo_regenerate_all_thumbnails_notice(); ?>

            <table class="tst_sizesTable">
                <tr>
                    <th>
                        Thumbnail ID
                    </th>
                    <th>
                        Size
                    </th>
                    <th>
                        Use focus point <a href="#aboutFocusPoint">(?)</a>
                    </th>
                    <th>
                        Crop
                    </th>
                    <th>
                        Crop-to-Fit <a href="#aboutCropToFit">(?)</a>
                    </th>
                    <th>
                        Crop-to-Fit background color <a href="#aboutCropToFit">(?)</a>
                    </th>
                </tr>

                <?php foreach ( $sizes as $key => $value ): ?>
                    <?php
                    $use_focus_point_name = 'use_focus_point_' . $key;
                    $crop_to_fit_name     = 'crop_to_fit_' . $key;
                    $sizes_options        = Options::get_sizes_options_for_thumbnail( $key );
                    ?>

                    <tr>
                        <td>
                            <?php echo $key; ?>
                        </td>
                        <td>
                            <?php echo $value['width'] . '&times;' . $value['height']; ?>
                        </td>
                        <td>
                            <select name="tst_sizes[<?php echo $use_focus_point_name; ?>]"<?php echo $disabled_if_free; ?>>
                                <?php
                                foreach ( Options::get_use_focus_point_options() as $option_key => $option_value ) {
                                    echo '<option value="' . $option_key . '"' . ( $sizes_options['use_focus_point_mode'] == $option_key ? 'selected' : '' ) . '>' . $option_value . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <?php echo $value['crop'] ? 'Yes' : 'No'; ?>
                        </td>
                        <td>
                            <?php if ( $value['crop'] ) : ?>
                                <select name="tst_sizes[<?php echo $crop_to_fit_name; ?>]"<?php echo $disabled_if_free; ?>>
                                    <?php
                                    foreach ( Options::get_crop_to_fit_options() as $option_key => $option_value ) {
                                        echo '<option value="' . $option_key . '"' . ( $sizes_options['crop_to_fit_mode'] == $option_key ? 'selected' : '' ) . '>' . $option_value . '</option>';
                                    }
                                    ?>
                                </select>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ( $value['crop'] ) : ?>
                                <input type="text"
                                       id="tst_cropToFitColor_<?php echo $key; ?>"
                                       name="tst_sizes[<?php echo $crop_to_fit_name; ?>_background_color]"
                                       value="<?php echo $sizes_options['crop_to_fit_background_color']; ?>"
                                    <?php echo $disabled_if_free; ?>>
                                <script>
                                    jQuery(document).ready(function ($) {
                                        $('#tst_cropToFitColor_<?php echo $key; ?>').wpColorPicker();
                                    });
                                </script>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <p class="submit">
                <input type="submit"
                       class="button-primary"
                       value="<?php _e( 'Save All Changes', 'theia-smart-thumbnails' ) ?>"
                    <?php echo $disabled_if_free; ?>/>
            </p>
        </form>

        <hr>

        <h3 id="aboutFocusPoint">About focus points <?php echo Misc::get_pro_only_notice(); ?></h3>

        <div id="poststuff">
            <div class="postbox">
                <div class="inside"><p>
                        Focus points can be set for each image individually, and will determine how its thumbnails are cropped.
                    </p>
                </div>
            </div>
        </div>
        <table class="tst_aboutCropToFit">
            <tr>
                <td>
                    <img src="<?php echo $images_url; ?>use-focus-point-original.png">
                    <br>
                    Original
                </td>
                <td>
                    <img src="<?php echo $images_url; ?>use-focus-point-before.png">
                    <br>
                    Without focus point
                </td>
                <td>
                    <img src="<?php echo $images_url; ?>use-focus-point-after.png">
                    <br>
                    With focus point
                </td>
            </tr>
        </table>
        <br>

        <hr>

        <h3 id="aboutCropToFit">About Crop-to-Fit <?php echo Misc::get_pro_only_notice(); ?></h3>

        <div id="poststuff">
            <div class="postbox">
                <div class="inside">
                    <p>
                        Enable this option for thumbnails that must have a fixed size without getting cropped. Instead, the
                        images will be resized and then filled with the specified background color.
                        <strong>Note that this disables the focus-point functionality.</strong>
                    </p>
                </div>
            </div>
        </div>
        <table class="tst_aboutCropToFit">
            <tr>
                <td>
                    <img src="<?php echo $images_url; ?>crop-to-fit-original.png">
                    <br>
                    Original
                </td>
                <td>
                    <img src="<?php echo $images_url; ?>crop-to-fit-before.png">
                    <br>
                    Crop
                </td>
                <td>
                    <img src="<?php echo $images_url; ?>crop-to-fit-after.png">
                    <br>
                    Crop to fit
                </td>
            </tr>
        </table>
        <?php
    }
}
