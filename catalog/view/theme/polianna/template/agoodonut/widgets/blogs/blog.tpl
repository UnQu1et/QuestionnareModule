<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
      <ul class="box-category">
<?php
if (count($myblogs) > 0) {
	if (!function_exists('buildTreeList')) {
		function buildTreeList($data, $del="") {
		//$del =$del."- ";
		?>
	  	        <ul>
		          <?php foreach ($data as $child) { ?>
		          <li>
		            <?php if ($child['active'] =='active' || $child['active'] == 'pass') { ?>
		            <a href="<?php echo $child['href']; ?>" class="active"> <?php echo $del.$child['name']; ?></a>
		            <?php } else { ?>
		            <a href="<?php echo $child['href']; ?>"> <?php echo $del.$child['name']; ?></a>
		            <?php } ?>

		          <?php
		                  if (isset($child["children"]) && count($child["children"]) > 0) {
		            			buildTreeList($child["children"], $del); //recursive
					      }
		          ?>
		          </li>
		          <?php } ?>
		        </ul>
		<?php
		}
	}

						if (!function_exists('makeTreeRecursive')) {
							function makeTreeRecursive($d, $r = 0, $pk = 'parent_id', $k = 'blog_id', $c = 'children') {
							  $m = array();

							  foreach ($d as $e) {

							    isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
							    isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
							    $m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));


							  }

							  return $m[$r];
							}

						}

						$categories = makeTreeRecursive($myblogs);


	?>

<?php

     foreach ($categories as $category) { ?>
      <li>
        <?php if ($category['active'] == 'active' || $category['active'] == 'pass') { ?>
        <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
        <?php } else { ?>
        <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
        <?php } ?>
        <?php if ($category['children']) { ?>
          <?php buildTreeList($category['children']);  ?>
        <?php } ?>
      </li>
      <?php }  } ?>

    	</ul>
	</div>
</div>
<style>
ul.box-category ul  {	border: none;
	padding: 0px;
}
.box-category > li > ul ul {
    padding: 0px;
    padding-left: 9px;
}

</style>