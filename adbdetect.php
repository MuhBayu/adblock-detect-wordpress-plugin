<?php
/**
* Plugin Name: adBDetect
* Plugin URI: https://github.com/MuhBayu/adblock-detect-wordpress-plugin
* Description: Plugin pendeteksi adBlock dan menampilkan notifikasi untuk mematikan terlebih dahulu.
* Version: 0.0.1
* Author: MuhBayu
* Author URI: https://bayyu.net
*/

function adbd_admin_actions() {
   add_options_page("Adblock Detect Setting", "Adblock Detect Setting", 1, "adbd_pluggin_setting", "adbd_admin");
}
add_action('admin_menu', 'adbd_admin_actions');

function sweetalert_header_src() {
   $adbb_title = get_option('adbd_title') ? get_option('adbd_title') : 'Ad-block Detect';
   $adbb_text  = get_option('adbd_text') ? get_option('adbd_text') : 'Please support this website by disabling your AdBlocker';
   $adbb_button  = get_option('adbd_button') ? get_option('adbd_button') : 'I understand, I have disabled my ad blocker.  Let me in!';
   $adbb_buttonColor  = get_option('adbd_buttonColor') ? get_option('adbd_buttonColor') : '#DD6B55';

   ob_start(); ?>
   <script type="text/javascript" src="https://cdn.rawgit.com/MuhBayu/adBDetect/628a81c4/js/adbdetect.packed.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.25.6/dist/sweetalert2.all.min.js"></script>
   <script>
   jQuery(window).load(function() {
      var adBD = adBDetect();
      if(adBD.isDetected()) {
         swal({
            title: "<?= $adbb_title; ?>", text: "<?= $adbb_text; ?>",
            type: "warning",
            confirmButtonColor: '<?= $adbb_buttonColor; ?>',
            allowOutsideClick: false,
            confirmButtonText:'<?= $adbb_button; ?>', showCancelButton: false,
         }).then((value) => { location.reload(); });
      }   
    });
    </script>
    <?php $contents = ob_get_clean();
    echo $contents;
}
add_action('wp_head', 'sweetalert_header_src');

function adbd_admin() {
   if(isset($_POST['submit'])) {
      update_option('adbd_title', strip_tags($_POST['adbd_title']));
      update_option('adbd_text', strip_tags($_POST['adbd_text']));
      update_option('adbd_button', strip_tags($_POST['adbd_button']));
      update_option('adbd_buttonColor', strip_tags($_POST['adbd_buttonColor']));
      echo '<div class="updated"><p><strong>Updated</strong>: Data berhasil diubah</p></div>';
   }
   $adbb_title = get_option('adbd_title');
   $adbd_text = get_option('adbd_text');
   $adbd_button = get_option('adbd_button') ? get_option('adbd_button') : 'I understand, I have disabled my ad blocker.  Let me in!';
   $adbb_buttonColor = get_option('adbd_buttonColor') ? get_option('adbd_buttonColor') : '#DD6B55';
?>
   <div class="wrap">
      <?= "<h1>" . __( 'Pengaturan adBDetect', 'adbd' ) . "</h1>"; ?>
      <form name="adbd_form" method="post">
      <table class="form-table">
         <tr>
            <th scope="row"><label for="adb-title">Ad-Block Title</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-title" name="adbd_title" value="<?= $adbb_title; ?>"></td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-text">Ad-Block Information</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-text" name="adbd_text" value="<?= $adbd_text; ?>"></td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-button">Ad-Block Button Text</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-button" name="adbd_button" value="<?= $adbd_button; ?>"></td>
         </tr>
         <tr>
            <th scope="row"><label for="adb-buttonColor">Ad-Block Button Color</label></th>
            <td><input type="text" class="regular-text ltr" id="adb-buttonColor" name="adbd_buttonColor" value="<?= $adbb_buttonColor; ?>"></td>
         </tr>
      </table>
      <p class="submit">
         <input type="submit" class="button button-primary" name="submit" value="<?php _e('Simpan Perubahan', 'adbd' ) ?>" />
      </p>
      </form>
   </div>
<?php } ?>