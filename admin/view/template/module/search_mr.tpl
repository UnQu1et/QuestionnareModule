<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $l->get('button_save'); ?></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $l->get('button_cancel'); ?></a>
      </div>
    </div>
    <div class="content">
    
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        
        <table id="general" class="list" style="width: 600px; margin: 0 auto;">
          <tbody >
            <tr>
              <td><?php echo $l->get('use_morphology'); ?></td>
              <td width="50">
                <input type="checkbox" name="search_mr_options[use_morphology]" value="1" <?php echo isset($options['use_morphology']) && $options['use_morphology'] ? "checked=checked" : "" ;?> />
              </td>
            </tr>

            <tr>
              <td><?php echo $l->get('use_relevance'); ?></td>
              <td width="50">
                <input type="checkbox" name="search_mr_options[use_relevance]" value="1" <?php echo isset($options['use_relevance']) && $options['use_relevance'] ? "checked=checked" : "" ;?> />
              </td>
            </tr>
            
            <tr>
              <td><?php echo $l->get('title_string_weight'); ?></td>
              <td width="50">
                <input type="text" name="search_mr_options[title_string_weight]" value="<?php echo isset($options['title_string_weight']) ? $options['title_string_weight'] : 0;?>">
              </td>
            </tr>

            <tr>
              <td><?php echo $l->get('title_word_weight'); ?></td>
              <td width="50">
                <input type="text" name="search_mr_options[title_word_weight]" value="<?php echo isset($options['title_word_weight']) ? $options['title_word_weight'] : 0;?>">
              </td>
            </tr>

            <tr>
              <td><?php echo $l->get('description_string_weight'); ?></td>
              <td width="50">
                <input type="text" name="search_mr_options[description_string_weight]" value="<?php echo isset($options['description_string_weight']) ? $options['description_string_weight'] : 0;?>">
              </td>
            </tr>
            
            <tr>
              <td><?php echo $l->get('description_word_weight'); ?></td>
              <td width="50">
                <input type="text" name="search_mr_options[description_word_weight]" value="<?php echo isset($options['description_word_weight']) ? $options['description_word_weight'] : 0;?>">
              </td>
            </tr>
            
            <tr>
              <td><?php echo $l->get('tag_word_weight'); ?></td>
              <td width="50">
                <input type="text" name="search_mr_options[tag_word_weight]" value="<?php echo isset($options['tag_word_weight']) ? $options['tag_word_weight'] : 0;?>">
              </td>
            </tr>
            
          </tbody>
        </table>

      </form>
    </div>
  </div>
</div>

<?php echo $footer; ?>
