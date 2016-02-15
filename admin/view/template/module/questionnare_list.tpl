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
    </div>
    <div class="content">
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_id; ?></td>
              <td class="left"><?php echo $entry_name; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php foreach ($questionnares as $questionnare) { ?>
          <tbody id="module-row">
            <tr>
              <td class="left"><?php echo $questionnare['id'] ?></td>
              <td class="left"><?php echo $questionnare['name'] ?></td>
              <td class="left"><?php echo $questionnare['isActive'] ? 'Да' : 'Нет'; ?></td>
              <td class="left" width="300px">
                  <a href="<?php echo $this->url->link('module/questionnare/edit', 'id=' . $questionnare['id'] . '&' . 'token=' . $this->session->data['token'], 'SSL');?>" class="button"><?php echo $button_edit; ?></a>
                  <a hreg="<?php echo $this->url->link('module/questionnare/delete',  'id=' . $questionnare['id'] . '&' . 'token=' . $this->session->data['token'], 'SSL');?>" class="button"><?php echo $button_remove; ?></a>
              </td>
            </tr>
          </tbody>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a href="<?php echo $this->url->link('module/questionnare/edit', 'token=' . $this->session->data['token'], 'SSL');?>" class="button"><?php echo $button_add_questionnare; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>