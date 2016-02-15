<div id="simplecheckout_login">
    <div class="simplecheckout-warning-block" <?php if (!$error_login) { ?>style="visibility:hidden;"<?php } ?>><?php echo $error_login ?></div>
    <table class="simplecheckout-login">
        <tr>
            <td class="simplecheckout-login-left"><?php echo $entry_email; ?></td>
            <td class="simplecheckout-login-right"><input type="text" name="email" value="<?php echo $email; ?>" /></td>
        </tr>
        <tr>
            <td class="simplecheckout-login-left"><?php echo $entry_password; ?></td>
            <td class="simplecheckout-login-right"><input type="password" name="password" value="" /></td>
        </tr>
        <tr>
            <td></td>
            <td class="simplecheckout-login-right"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></td>
        </tr>
        <tr>
            <td></td>
            <td class="simplecheckout-login-right buttons"><a id="simplecheckout_button_login" onclick="simplecheckout_login()" class="button btn"><span><?php echo $button_login; ?></span></a></td>
        </tr>
    </table>
</div>
<script type='text/javascript'>
$('.simplecheckout-login input').keydown(function(e) {
    if (e.keyCode == 13) {
        simplecheckout_login();
    }
});
</script>
<?php if ($redirect) { ?>
<script type='text/javascript'>
location = '<?php echo $redirect ?>';
</script>
<?php } ?>
<!-- You can add here the social engine code for login in the simplecheckout_customer.tpl -->
    