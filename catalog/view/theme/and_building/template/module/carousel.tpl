<div style="padding-left:10px;"><div class="jcarousel manufac" style=" width:100%; position:relative; margin:0 auto;">
                    <ul>
    <?php foreach ($banners as $banner) { ?>
    <li style="width:190px!important; text-align:center;"><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></li>
    <?php } ?>
  </ul>
<a style="position:absolute;left:5px; top:52%;  " href="#" class="jcarousel-control-prev">&lsaquo;</a>
<a style="position:absolute;right:5px; top:52%;" href="#" class="jcarousel-control-next">&rsaquo;</a>
</div>
</div>
