<style type="text/css">
    #questionnare-background {
        position: fixed;
        height: 100%;
        width: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 100;
        overflow: auto;
        white-space: nowrap;
        top: 0;
        left: 0;
        text-align: center;
        font-size: 0;
    }
    #questionnare-background:before {
        height: 100%;
        display: inline-block;
        vertical-align: middle;
        content: '';
    }
    #questionnare {
        display: inline-block;
        vertical-align: middle;
        margin: 10px 0 0 10px;
        max-width: 50%;
        background: #fff;
        text-align: left;
        display: inline-block;
        white-space: normal;
        padding: 20px;
        border-radius: 10px;
        position: relative;
    }
    #questionnare-background .close {
        position: absolute;
        display: inline-block;
        height: 30px;
        width: 30px;
        vertical-align: top;
        top: -10px;
        right: -10px;
        margin-left: -15px;
        background-image: url('catalog/view/theme/default/image/closebtn.png');
        cursor: pointer;
    }
    td.question {
        vertical-align: top;
        width: 30%;
    }
    td.answer {
        vertical-align: top;
        width: 70%;
    }
    #questionnare-form textarea {
        width: 100%;
        resize: none;
    }
    #questionnare tr {
        height: 30px;
    }
    #questionnare-background p {
        font-size: 20px;
        margin-bottom: 50px;
    }
    .formfooter {
        margin-top: 20px;
        text-align: center;
    }
    .submitbtn {
        display: inline;
        font-size: 20px;
        border-radius: 5px;
        border: 0px; 
        background-color: #fb6335;
        color: #fff;
        cursor: pointer;
    }
</style>
<div id="questionnare-background">
    <div id="questionnare">
        <p>Уважаемые пользователи! В рамках повышения качества обслуживания и работы сайта, просим пройти вас небольшой опрос:</p>
        <form id="questionnare-form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="questionnare-form">
            <table>
                <?php foreach ($questions as $question) { ?>
                <tr>
                    <td class="question"><?php echo $question['question']; ?><td>
                    <td class="answer"><?php if ($question['type'] == 2) { ?>
                        <input type="radio" name="<?php echo $question['id']; ?>" value="1">1
                        <input type="radio" name="<?php echo $question['id']; ?>" value="2">2
                        <input type="radio" name="<?php echo $question['id']; ?>" value="3">3
                        <input type="radio" name="<?php echo $question['id']; ?>" value="4">4
                        <input type="radio" name="<?php echo $question['id']; ?>" value="5" checked>5
                        <?php } else { ?>
                        <textarea name="<?php echo $question['id']; ?>" maxlength="1000" rows="3"></textarea>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <div class="formfooter">
                <button class="submitbtn" type="submit">Отправить</button>
            </div>
        </form>
        <div class="close"></div>
    </div>
</div>
<script type="text/javascript">
(function()
{
    $("#questionnare-background .close").click(function () {
        var date = new Date(new Date().getTime() + 60 * 60 * 24);
        document.cookie = "QuestionnareHoldOwer=; path=/; expires=" + date.toUTCString();
        $("#column-left").remove();
    });
    var postData = $("form#questionnare-form").serialize();
    $("#questionnare-form .submitbtn").click(function (event) {
        $.post({
            url: "<?php echo $action; ?>",
            data: postData,
            success: function (data) {
                $("#questionnare-form, .close").remove();
                $("#questionnare p").html(data);
                $("#questionnare").append("<button class=\"okbtn\">Ок</button>");
                $("#questionnare .okbtn").click(function (){
                    $("#column-left").remove();
                });
            }
        });
        event.preventDefault();
    });
})();    
</script>

