<?php
if ($common) {$post = serialize($_POST);
$get = serialize($_GET);
?>
<script>
$(document).ready(function(){
 $.ajax({  type: 'POST',
			url: '<?php echo $admin_path; ?>index.php?route=module/blog/blogadmin&token=<?php echo $token; ?>',
			dataType: 'html',
			data: { post: '<?php echo base64_encode($post);?>', get: '<?php echo base64_encode($get);?>' },
		   	success: function(data)
		    {
		      $('#blogadmin').html(data);
		      $('#scriptblogadmin').html('');
  		    }
	    });

});
</script>
<?php } ?>