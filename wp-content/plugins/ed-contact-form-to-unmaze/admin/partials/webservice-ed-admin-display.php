<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       escolhadigital.com
 * @since      1.0.0
 *
 * @package    Webservice_Ed
 * @subpackage Webservice_Ed/admin/partials
 * 
 *
 *  
 *    
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap_ed">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

  <form method="post" name="webservice_options" action="options.php">

    <?php
    // Grab all options
    $options = get_option($this->plugin_name);
    ?>

    <?php
    settings_fields($this->plugin_name);
    do_settings_sections($this->plugin_name);
    ?>

    <h2>Configuration</h2>

    <fieldset>
      <label for="<?php echo $this->plugin_name; ?>-apikey">API Key</label>
      <input type="input" id="<?php echo $this->plugin_name; ?>-apikey" name="<?php echo $this->plugin_name; ?>[apikey]" value="<?php echo $options['apikey']; ?>" />
    </fieldset>

    <fieldset>
      <label for="<?php echo $this->plugin_name; ?>-email">Email</label>
      <input type="input" id="<?php echo $this->plugin_name; ?>-email" name="<?php echo $this->plugin_name; ?>[email]" value="<?php echo $options['email']; ?>" />
      <span>Example: usrnet</span>
    </fieldset>

    <fieldset>
      <label for="<?php echo $this->plugin_name; ?>-url">Url</label>
      <input type="input" id="<?php echo $this->plugin_name; ?>-url" name="<?php echo $this->plugin_name; ?>[url]" value="<?php echo $options['url']; ?>" />
      <!--<span>https://xxxxxx.unmaze.io</span>-->
    </fieldset>

    <fieldset>
      <label for="<?php echo $this->plugin_name; ?>-fields">Fields</label>
      <input type="input" id="<?php echo $this->plugin_name; ?>-fields" name="<?php echo $this->plugin_name; ?>[fields]" value="<?php echo $options['fields']; ?>" />
      <span>Example: name, email, phoneNumber, cidade_2, origem_campanha</span>
    </fieldset>

    <hr />

    <?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>

  </form>
</div>