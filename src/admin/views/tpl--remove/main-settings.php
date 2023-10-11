<?php
   defined('ABSPATH') || die('');
   
   $latestLinguiseError = '';
   ?>
   
    <form action="" method="post">
	 <?php wp_nonce_field('rytme-settings', 'main_settings'); ?>
<div class="content">
   <?php if ($latestLinguiseError) : ?>
   <div class="rytme-settings-option full-width">
      <label style="color: red;" class="rytme-setting-label label-bolder rytme-tippy">
      <?php esc_html_e('Linguise latest error', 'rytme'); ?><span class="material-icons">error_outline</span>
      </label>
      <div style="width: 100%;display: inline-block;margin: 10px 0;padding-left: 15px;">
         <?php esc_html_e('Linguise returned an error in the last past 5 minutes, click on the link to get more information', 'rytme'); ?> :
         <strong><a href="<?php echo esc_url('https://www.linguise.com/documentation/debug-support/wordpress-plugin-error-codes/#' . $latestLinguiseError['code']); ?>" target="_blank" title="<?php esc_attr('Get more information about this error on Linguise', 'rytme');?>">
         <?php echo esc_html($latestLinguiseError['message']); ?>
         </a>
         </strong>
      </div>
   </div>
   <?php endif; ?>
   <div class="rytme-settings-option full-width">
      <label for="login"
         class="rytme-setting-label label-bolder rytme-tippy"><?php esc_html_e('Enter rytr.me login & pswd', 'rytme'); ?></label>
      <p style="width: 100%;display: inline-block"></p>
      <div style="padding: 10px">
         <label ><?php esc_html_e('login', 'rytme'); ?></label>  
         <input type="text" class="rytme-input custom-input" name="rytme_options[login]"
            id="login"
            value="<?php echo isset($options['login']) ? esc_html($options['login']) : '' ?>"/>
         <p style="width: 100%;display: inline-block"></p>
         <label ><?php esc_html_e('pwsd', 'rytme'); ?></label>  
         <input type="text" class="rytme-input custom-input" name="rytme_options[pwsd]"
            id="pwsd"
            value="<?php echo isset($options['pwsd']) ? esc_html($options['pwsd']) : '' ?>"/>	

<p style="width: 100%;display: inline-block"></p>
         <label ><?php esc_html_e('fp', 'rytme'); ?></label>  
         <input type="text" class="rytme-input custom-input" name="rytme_options[fp]"
            id="fp"
            value="<?php echo isset($options['fp']) ? esc_html($options['fp']) : '' ?>"/>				
         <p style="width: 100%;display: inline-block"></p>
         <input type="submit"
            class="rytme-button blue-button waves-effect waves-light small-radius small-button"
            id="token_apply" value="<?php esc_html_e('Verify', 'rytme'); ?>"/>
      </div>
	  
	  <p class="submit" style="margin-top: 10px;margin-right: 10px;display: inline-block;float: right; width: 100%;'"><input
   type="submit"
   name="rytme_submit"
   id="submit"
   class="button button-primary"
   value="<?php esc_html_e('Save Settings', 'rytme'); ?>"></p>
   </div>
</div>

   
    </form>
