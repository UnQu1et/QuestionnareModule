<?php if (count($custom)) { ?>
<h2>Simple Data</h2>
<form action="<?php echo $action ?>" id="<?php echo $form_id ?>" method="POST">
<table class="form simple-custom-form">
<?php foreach ($custom as $id => $field) { ?>
  <tr>
    <td><?php echo $field['label']; ?></td>
    <td>
        <?php if ($field['type'] == 'text' || $field['type'] == 'date') { ?>
            <input type="text" name="<?php echo $field['id'] ?>" value="<?php echo $field['value'] ?>" <?php echo $field['type'] == 'date' ? 'class="datepicker"' : '' ?>>
        <?php } ?>
        <?php if ($field['type'] == 'textarea') { ?>
            <textarea name="<?php echo $field['id'] ?>"><?php echo $field['value'] ?></textarea>
        <?php } ?>
        <?php if ($field['type'] == 'select' || $field['type'] == 'select_from_api') { ?>
            <select name="<?php echo $field['id'] ?>">
                <?php foreach ($field['values'] as $key => $value) { ?>
                    <option value="<?php echo $key ?>" <?php echo $key == $field['value'] ? ' selected="selected"' : ''?>><?php echo $value ?></option>
                <?php } ?>
            </select>
        <?php } ?>
        <?php if ($field['type'] == 'radio' || $field['type'] == 'radio_from_api') { ?>
            <?php foreach ($field['values'] as $key => $value) { ?>
                <label><input type="radio" name="<?php echo $field['id'] ?>" value="<?php echo $key ?>" <?php echo $key == $field['value'] ? ' checked="checked"' : ''?>>&nbsp;<?php echo $value ?></label><br>
            <?php } ?>
        <?php } ?>
        <?php if ($field['type'] == 'checkbox' || $field['type'] == 'checkbox_from_api') { ?>
            <?php foreach ($field['values'] as $key => $value) { ?>
                <label><input type="checkbox" name="<?php echo $field['id'] ?>[]" value="<?php echo $key ?>" <?php echo is_array($field['value']) && in_array($key, $field['value']) ? ' checked="checked"' : ''?>>&nbsp;<?php echo $value ?></label><br>
            <?php } ?>
        <?php } ?>
        <?php if ($field['type'] == 'hidden') { ?>
            <input type="hidden" name="<?php echo $field['id'] ?>" value="<?php echo $field['value'] ?>">
        <?php } ?>
    </td>
  </tr>
<?php } ?>
<?php if (count($custom)) { ?>
  <tr>
    <td></td>
    <td><a class="button" onclick="submit_<?php echo $form_id ?>();return false;"><span><?php echo $button_save ?></span></a></td>
  </tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
    function submit_<?php echo $form_id ?>() {
        var data = $('#<?php echo $form_id ?>').find('input,select,textarea').serialize();

        $.ajax({
            url: '<?php echo htmlspecialchars_decode($action) ?>',
            data: data,
            type: 'POST',
            dataType: 'text',
            beforeSend: function() {
                $('#<?php echo $form_id ?> a.button').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
            },      
            success: function(data) {
                $('#<?php echo $form_id ?>').parents('.simple-container').html(data);
                $('#<?php echo $form_id ?> span.wait').remove();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $('#<?php echo $form_id ?> span.wait').remove();
            }
        });
    }
    $(function(){
        $('.simple-custom-form .datepicker').datepicker();
    });
</script>
<?php } ?>