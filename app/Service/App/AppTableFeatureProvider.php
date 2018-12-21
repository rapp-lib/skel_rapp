<?php
namespace R\App\Service\App;
use R\Lib\Table\Feature\BaseFeatureProvider;

class AppTableFeatureProvider extends BaseFeatureProvider
{

    /**
     * @hook chain
     * 未承認のみを対象とする
     */
    public function on_read_colAcceptFlg ($query, $col_name)
    {
        if (false) {
            $query->where($query->getTableName().".".$col_name, 2);
        }
    }

    /**
     * @hook on_read
     * ユーザ表示項目を関連付ける
     */
    protected function on_read_colStatus ($query, $col_name)
    {
        if (! app()->user->getCurrentPriv("admin")) {
            $query->where($query->getTableName().".".$col_name, 1);
        }
    }

    /**
     * @hook on_read
     * ユーザ表示項目を関連付ける
     */
    protected function on_read_colReleaseDate ($query, $col_name)
    {
        if (! app()->user->getCurrentPriv("admin")) {
            $query->where($query->getTableName().".".$col_name." <= CURRENT_DATE");
        }
    }
}
