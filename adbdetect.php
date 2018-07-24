<?php
/**
* Plugin Name: adBDetect
* Plugin URI: https://github.com/MuhBayu/adblock-detect-wordpress-plugin
* Description: Plugin pendeteksi adBlock dan menampilkan notifikasi untuk mematikan terlebih dahulu.
* Version: 0.0.1
* Author: MuhBayu
* Author URI: https://bayyu.net
*/
defined('ABSPATH') or exit('No direct script access allowed');

$adbd_option = array(
   'active' => get_option('adbd_active') ? get_option('adbd_active') : 'yes',
   'type' => get_option('adbd_type') ? get_option('adbd_type') : 'warning',
   'title' => get_option('adbd_title') ? get_option('adbd_title') : 'Ad-block Detect',
   'text' => get_option('adbd_text') ? get_option('adbd_text') : 'Please support this website by disabling your AdBlocker',
   'button' => get_option('adbd_button') ? get_option('adbd_button') : 'I understand, I have disabled my ad blocker.  Let me in!',
   'buttonColor' => get_option('adbd_buttonColor') ? get_option('adbd_buttonColor') : '#DD6B55'
);
$adbd_active_checked = ($adbd_option['active'] == 'yes') ? 'checked' : '';

function adbd_setting_actions() {
   add_options_page("Adblock Detect Setting", "Adblock Detect Setting", 'manage_options', "adbd_pluggin_setting.php", "adbd_setting_page");
}
function sweetalert_header_src() {
   global $adbd_option;
   ob_start(); 
   if($adbd_option['active'] == 'yes') :
?>   
   <script type="text/javascript" src="https://cdn.rawgit.com/MuhBayu/adBDetect/628a81c4/js/adbdetect.packed.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.25.6/dist/sweetalert2.all.min.js"></script>
   <script>
   jQuery(window).load(function() {
      var adBD = adBDetect();
      if(adBD.isDetected()) {
         swal({
            title: "<?= $adbd_option['title']; ?>", text: "<?= $adbd_option['text']; ?>",
            type: "<?= $adbd_option['type'];?>",
            confirmButtonColor: "<?= $adbd_option['buttonColor']; ?>",
            allowOutsideClick: false,
            confirmButtonText:"<?= $adbd_option['button']; ?>", showCancelButton: false,
         }).then((value) => { location.reload(); });
      }   
   });
   </script>
<?php endif; ?>
   <?php $contents = ob_get_clean();
   _e($contents);
}
add_action('wp_head', 'sweetalert_header_src');

function adbd_setting_page() {
   global $adbd_option;
   if(isset($_POST['submit'])) {
      update_option('adbd_active', strip_tags(trim($_POST['adbd_active'])));
      update_option('adbd_type', strip_tags(trim($_POST['adbd_type'])));
      update_option('adbd_title', strip_tags(trim($_POST['adbd_title'])));
      update_option('adbd_text', strip_tags(trim($_POST['adbd_text'])));
      update_option('adbd_button', strip_tags(trim($_POST['adbd_button'])));
      update_option('adbd_buttonColor', strip_tags(trim($_POST['adbd_buttonColor'])));
      _e('<div class="updated"><p><strong>Updated</strong>: Data successfully changed.</p></div>');
   }
?>
   <div class="wrap">
      <?= "<h1>" . __( 'adBDetect Settings', 'adbd' ) . "</h1>"; ?>
      <hr/>
      <form name="adbd_form" method="post">
      <table class="form-table">
         <tr>
            <th scope="row"><label for="adb-active">Ad-Block Detect</label></th>
            <td>
         <?php if ($adbd_option['active'] == 'yes') : ?>
               <input type="radio" name="adbd_active" value="yes" checked><span class="value"><strong>Enable</strong></span>
               <input type="radio" name="adbd_active" value="no"><span class="value">Disable</span>
         <?php else: ?>
               <input type="radio" name="adbd_active" value="yes"><span class="value"><strong>Enable</strong></span>
               <input type="radio" name="adbd_active" value="no" checked><span class="value">Disable</span>
         <?php endif; ?>
               <p class="description">Enable or disable adBDetect</p>
            </td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-type">Popup Types</label></th>
            <td>
               <select name="adbd_type" value="<?= $adbd_option['type'];?>">
               <?php foreach (array('warning', 'info', 'error', 'question') as $key => $value) {
                  $selected = ($value == $adbd_option['type']) ? 'selected':'';
                  echo "<option value=\"$value\" $selected>".ucfirst($value)."</option>";
               }
               ?>
               </select>
            </td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-title">Title</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-title" name="adbd_title" value="<?= $adbd_option['title']; ?>"></td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-text">Information</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-text" name="adbd_text" value="<?= $adbd_option['text']; ?>"></td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-button">Button Text</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-button" name="adbd_button" value="<?= $adbd_option['button']; ?>"></td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-buttonColor">Button Color</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-buttonColor" name="adbd_buttonColor" value="<?= $adbd_option['buttonColor']; ?>"></td>
         </tr>
      </table>
      <p class="submit">
         <input type="submit" class="button button-primary" name="submit" value="<?php _e('Save Changes', 'adbd' ) ?>" />
      </p>
      </form>
   </div>
<?php } ?>

<?php
add_action('admin_menu', 'adbd_setting_actions');
?>