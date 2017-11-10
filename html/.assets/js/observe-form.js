
// フォームにルール定義/エラーを適用する
window.observeForm = function($form, state){
    return new FormObserver($form, state, {
        apply_rule_callback: function($input, rule){
            $input.closest("tr").addClass("rule-"+rule.type);
        },
        apply_error_callback: function($input, error){
            var msg = error.message;
            var $field_block = $input.closest("td");
            // 既存メッセージがあれば重複させない
            var $exist_msgs = $field_block.find(".errmsg").filter(function(){
                return $(this).text() == msg;
            });
            if ($exist_msgs.length == 0) {
                // .errmsgブロックの追加
                $field_block.addClass("has-error");
                $field_block.append($('<div>').addClass('errmsg').addClass('error-'+error.type).text(msg));
            }
        }
    });
};
