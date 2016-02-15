<div class="box">
	  <div class="box-category">
<?php
if (count($myblogs)>0) {
	foreach ($myblogs as $blogs) {		for ($i=0; $i<$blogs['flag_start']; $i++) {
?>		<ul class="padding_<?php  echo $blogs['level'];?>" style="<?php if(!$blogs['display']) echo 'display:none;' ?>">
			<li><a href="<?php if($blogs['active']=='active') echo $blogs['href']."#";  else echo $blogs['href']; ?>" class="<?php if($blogs['active']=='active') echo 'child-active'; if($blogs['active']=='pass') echo ''; ?>"><?php echo $blogs['name']; if ($blogs['count']>0) echo  " (".$blogs['count'].")"; ?></a>
<?php
			for ($m=0; $m<$blogs['flag_end']; $m++) {
?> 			</li>
		</ul>
<?php
			}
		}
	}
}
?>

	</div>
</div>