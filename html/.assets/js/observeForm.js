
// フォームにルール定義/エラーを適用する
window.observeForm = function($form, state, mode){
    appendStyle(
        ' .errmsg { color:#ff4422; } '+
        ' .has-error { background-color:#ffeeee; } '+
        ' .rule-required > :first-child:after { content:" ※"; color:#f00 ; } ');
    return new FormObserver($form, state, {
        refresh_callback: function(self, $input, rules, errors){
            var $block = $input.closest("tr");
            for (var i in rules) {
                var rule = rules[i];
                if (self.getValidator().checkRuleIf(rule, $input)) {
                    $block.addClass("rule-"+self.className(rule.type));
                    if ($input.hasClass("changed")) {
                        self.getValidator().applyRule(rule, $input);
                        $input.removeClass("changed");
                    }
                } else {
                    $block.removeClass("rule-"+self.className(rule.type));
                }
            }
            var $block = $input.closest("td");
            for (var i in errors) {
                var error = errors[i];
                var $msg = $block.find('.errmsg.error-'+self.className(error.type));
                if (error.state=="remove") {
                    $msg.remove();
                    delete errors[i];
                } else {
                    if ( ! $msg.length) {
                        $msg = $('<div>').addClass('errmsg')
                            .addClass('error-'+self.className(error.type)).text(error.message);
                        $block.append($msg);
                    }
                }
            }
            if (errors.length) $block.addClass("has-error");
            else $block.removeClass("has-error");
        }
    });
};
