<?php echo $header; ?>
<style type="text/css">
    thead *, tbody * {
        font-size: 16px;
    }
</style>
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
          <h1><img src="view/image/module.png" alt="" /> <?php echo isset($questionnare[0]['id']) ? $questionnare[0]['id'] : 'Новый опрос'; ?></h1>
          <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-main"><?php echo 'Настройки'; ?></a>
                <a href="#tab-questions"><?php echo 'Вопросы'; ?></a>
                <a href="#tab-results"><?php echo 'Результаты'; ?></a>
            </div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"> 
                <div id="tab-main">
                    <table style="margin-bottom: 20px; vertical-align: center;">
                        <tr style="display: none;">
                            <td class="left">
                                ID:
                            </td>
                            <td class="left">
                                <input type="number" size="3" value="<?php echo $questionnare[0]['id']; ?>" name="questionnare[id]" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td class="left">
                                Имя:
                            </td>
                            <td class="left">
                                <input type="text" size="50" value="<?php echo $questionnare[0]['name']; ?>" name="questionnare[name]">
                            </td>
                        </tr>
                        <tr>
                            <td class="left">
                                Активен:
                            </td>
                            <td class="left">
                                <input type="checkbox" style="margin-left: 0px;" name="questionnare[isActive]" <?php echo $questionnare[0]['isActive'] ? 'checked' : ''; ?>></input>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="tab-questions">
                    <table id="questions" class="list">
                      <thead>
                        <tr>
                          <td style="display:none;">ID:</td>
                          <td class="left">Вопрос:</td>
                          <td class="left">Тип:</td>
                          <td></td>
                        </tr>
                      </thead>
                      <?php $module_row = 0; ?>
                      <?php foreach ($questions as $question) { ?>
                      <tbody id="row<?php echo $module_row; ?>">
                        <tr>
                          <td class="left" style="display:none">
                              <input type="number" size="3" value="<?php echo $question['id']; ?>" name="question[<?php echo $module_row; ?>][id]" readonly>
                          </td>
                          <td class="left">
                              <input type="text" size="50" value="<?php echo $question['question']; ?>" name="question[<?php echo $module_row; ?>][question]">
                          </td>
                          <td class="left">
                              <select name="question[<?php echo $module_row; ?>][type]">
                                  <?php if($question['type'] == 2){ ?>
                                  <option value="2" selected="selected">Оценка</option>
                                  <option value="1">Текст</option>
                                  <?php } else { ?>
                                  <option value="2">Оценка</option>
                                  <option value="1" selected="selected">Текст</option>
                                  <?php } ?>
                          </td>
                        </tr>
                      </tbody>
                      <?php $module_row++; ?>
                      <?php } ?>
                      <tfoot>
                        <tr>
                          <td colspan="2"></td>
                          <td class="left"><a onclick="addQuestion();" class="button"><?php echo $button_add_question; ?></a></td>
                        </tr>
                      </tfoot>
                    </table>
                    <script type="text/javascript">
                        var module_row = <?php echo $module_row; ?>;

                        function addQuestion() {	
                                html  = '<tbody id="row' + module_row + '">';
                                html += '  <tr>';
                                html += '    <td class="left" style="display:none"><input type="number" size="3" value="" name="question[' + module_row + '][id]" disabled></td>';
                                html += '    <td class="left"><input type="text" size="50" value="" name="question[' + module_row + '][question]"></td>';
                                html += '    <td class="left"><select name="question[' + module_row + '][type]">';
                                html += '      <option value="2" selected="selected">Оценка</option>';
                                html += '      <option value="1">Текст</option>';
                                html += '    </select></td>';
                                html += '    <td class="left"><a onclick="$(\'#row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
                                html += '  </tr>';
                                html += '</tbody>';

                                $('#questions tfoot').before(html);

                                module_row++;
                        }
                    </script> 
                </div>
                <div id="tab-results">
                <?php foreach ($answers as $answer) { ?>
                <p><?php echo $answer['user']; ?></p>
                <p><?php echo $answer['question']; ?></p>
                <p><?php echo $answer['answer']; ?></p>
                <?php } ?>
            </div>
            </form>
        </div>
    </div>
<script type="text/javascript">
$('#tabs a').tabs();
</script>
</div>
<?php echo $footer; ?>