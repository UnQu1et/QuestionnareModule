<div id="blog-menu_admin" style="display: none;">

      <li id="help"><a href="<?php echo $url_module; ?>" class="top"><?php echo $url_module_text; ?></a>
        <ul>
          <li><a href="<?php echo $url_blog; ?>"><?php echo $url_blog_text; ?></a></li>
          <li><a href="<?php echo $url_record; ?>"><?php echo $url_record_text; ?></a></li>
          <li><a href="<?php echo $url_comment; ?>"><?php echo $url_comment_text; ?></a></li>

          <li><a href="<?php echo $url_forum; ?>" style="word-wrap: normal; "><?php echo $url_forum_text; ?></a></li>

          <li><a href="<?php echo $url_forum_buy; ?>" style="word-wrap: normal; "><?php echo $url_forum_buy_text; ?></a></li>
          <li><a href="<?php echo $url_forum; ?>" style="word-wrap: normal; "><?php echo $url_forum_site_text; ?></a></li>
          <li><a href="<?php echo $url_forum_buy; ?>" style="word-wrap: normal; "><?php echo $url_forum_update_text; ?></a></li>
          <li><a href="<?php echo $url_opencartadmin; ?>" style="word-wrap: normal; "><?php echo $url_opencartadmin_text; ?></a></li>

        </ul>
      </li>
    </ul>


</div>
<a href="http://opencartadmin.com">www.opencartadmin.com</a>

<script>
$(document).ready(function(){
var blog_menu_admin = $('#blog-menu_admin').html();
$('#menu ul:first').append(blog_menu_admin);

	$('#menu > ul').superfish({
		hoverClass	 : 'sfHover',
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {height: 'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false,
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});

	$('#menu > ul').css('display', 'block');



$('#blogadmin').html();
});
</script>