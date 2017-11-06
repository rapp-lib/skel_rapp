// エラーメッセージを表示する
window.showErrors = function(o){
    var errors = o.errors;
    var $form = o.$form;
    for (var i in errors) {
        var $input =$form.find('[name="'+errors[i].name_attr+'"]');
        var $fieldBlock = $input.closest(".input-area");
        var msg = "※"+errors[i].message;
        // 対応するInputがなければ処理しない
        if ($input.length==0 || $fieldBlock.length==0) continue;
        // 既存メッセージがあれば重複させない
        var msg_exists = false;
        $fieldBlock.find(".errmsg").each(function(){
            if ($(this).text()==msg) msg_exists = true;
        });
        if (msg_exists) continue;

        var $msgbox =$('<p>').addClass('errmsg').text(msg);
        $fieldBlock.addClass("has-error");
        $fieldBlock.append($msgbox);
    }
};
// CSSの追加
appendStyle(
    '.input-area.errmsg { color: #ff4422; } '+
    '.input-area.has-error { background-color: #ffeeee; } ');
