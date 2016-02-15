<?php if (count($languages) > 1) { ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		<div id="language" >
			<select class="chosen-select-no-single" style="margin-top:7px;" id="languages" name="languages" onChange="$('input[name=\'language_code\']').attr('value', $('#languages option:selected').val()
).submit(); $(this).parent().parent().submit();">
			<?php foreach ($languages as $language) { ?>
            	<?php if ($language['code'] == $language_code) { ?>
                    	<option class="option" selected value="<?php echo $language['code']; ?>"><?php echo $language['code']; ?></option>
            	<?php }else{  ?>
                    	<option class="option" value="<?php echo $language['code']; ?>"><?php echo $language['code']; ?></option>
				<?php } ?>
            <?php } ?>
            </select>
            <input type="hidden" name="language_code" value="" />
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		</div>
	</form>
<?php } ?>
