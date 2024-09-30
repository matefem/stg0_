<?php

use WPStaging\Framework\Facades\UI\Checkbox;

/**
 * @var \WPStaging\Core\Forms\Form $form
 */

?>
<?php if (defined('WPSTG_ENABLE_COMPRESSION') && constant('WPSTG_ENABLE_COMPRESSION')) : ?>
<tr class="wpstg-settings-row">
    <td class="wpstg-settings-row th" colspan="2">
        <div class="col-title">
            <strong><?php
                echo esc_html('Backups') ?></strong>
            <span class="description"></span>
        </div>
    </td>
</tr>
<!-- Compressed Backups -->
<tr class="wpstg-settings-row">
    <td class="wpstg-settings-row th">
        <div class="col-title">
            <?php
            $form->renderLabel("wpstg_settings[enableCompression]") ?>
            <span class="description">
                <?php
                echo wp_kses_post(
                    __(
                        'Compresses the backup to reduce size. Especially useful with big databases.',
                        'wp-staging'
                    )
                ); ?>
            </span>
        </div>
    </td>
    <td>
        <?php
        $form->renderInput("wpstg_settings[enableCompression]") ?>
    </td>
</tr>
<?php endif; ?>
