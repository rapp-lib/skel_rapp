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
    protected function on_read_colAcceptStatus ($query, $col_name)
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

    public function alias_productFiles ($result, $src_values, $alias)
    {
        if (count($src_values) === 0) return array();
        // 製品に紐づく関連ファイルを取得
        $entries = table("ProductFile")->findBy(array("product_id"=>$src_values))->select();
        report($entries);

        foreach($entries as $k => $v) {
            $file = app()->file->getStorage("public")->getFileByUri($v["file"]);
            $stream = $file ? $file->getSource() : null;
            if($stream){
                $size = floor(filesize($stream) / 1024 / 1024 * 10) / 10;
                $v["file_size"] =$size;
            }
            $dest_values[$v["product_id"]][$v["id"]] = $v;
        }
        return $dest_values;
    }

    public function getCsvdlList () {

        // $this->findBy("accept_flg","2")->select(array(
        //     "download_flg",
        //     "erase_flg",
        // ));
    }
}
