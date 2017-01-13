<?php

// NOTE admin settings
// see example https://developer.wordpress.org/plugins/settings/custom-settings-page/

/**
 * custom option and settings
 */
function custom_plugin_settings_init()
{
    // register a new setting for "custom_plugin" page
    register_setting('custom_plugin', 'custom_plugin_options');

    // register a new section in the "custom_plugin" page
    add_settings_section(
        'custom_plugin_section_developers',
        __('Settings.', 'custom_plugin'),
        'custom_plugin_section_developers_cb',
        'custom_plugin'
    );

    // register a new field in the "custom_plugin_section_developers" section, inside the "custom_plugin" page
    add_settings_field(
        'custom_plugin_field_api', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __('Enter your API Key', 'custom_plugin'),
        'custom_plugin_field_api_cb',
        'custom_plugin',
        'custom_plugin_section_developers',
        [
            'label_for'         => 'custom_plugin_field_api',
            'class'             => 'custom_plugin_row',
            'custom_plugin_custom_data' => 'custom',
        ]
    );
}

/**
 * register our custom_plugin_settings_init to the admin_init action hook
 */
add_action('admin_init', 'custom_plugin_settings_init');

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function custom_plugin_section_developers_cb($args)
{
    ?>
    <p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Configure your Giant Bomb settings.', 'custom_plugin'); ?></p>

		<p>Get your API Key from <a href="http://www.giantbomb.com/api">http://www.giantbomb.com/api</a></p>

    <?php
}

// api field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function custom_plugin_field_api_cb($args)
{
    // get the value of the setting we've registered with register_setting()
    $options = get_option('custom_plugin_options');
    // output the field

		// print_r($options);
    ?>

		<input id="<?= esc_attr($args['label_for']); ?>"
            data-custom="<?= esc_attr($args['custom_plugin_custom_data']); ?>" name="custom_plugin_options[<?= esc_attr($args['label_for']); ?>]" />

		<?php echo $options['custom_plugin_field_api'] ? "<p>Your API Key: <em>{$options['custom_plugin_field_api']}</em></p>" : ''; ?>

    <?php
}

/**
 * top level menu
 */
function custom_plugin_options_page()
{
    // add top level menu page
    add_menu_page(
        'Vimeo Videos',
        'Vimeo Video Options',
        'manage_options',
        'custom_plugin',
        'custom_plugin_options_page_html'
    );
}

/**
 * register our custom_plugin_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'custom_plugin_options_page');

/**
 * top level menu:
 * callback functions
 */
function custom_plugin_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('custom_plugin_messages', 'custom_plugin_message', __('Settings Saved', 'custom_plugin'), 'updated');
    }

    // show error/update messages
    settings_errors('custom_plugin_messages');
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "custom_plugin"
            settings_fields('custom_plugin');
            // output setting sections and their fields
            // (sections are registered for "custom_plugin", each field is registered to a specific section)
            do_settings_sections('custom_plugin');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}
