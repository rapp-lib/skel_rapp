<?php

/**
 * @model
 * @table FavoliteProductAssoc */
class FavoliteProductAssocModel extends Model_App
{
    /**
     * ID指定で1件取得
     */
    public function get_by_id ($id)
    {
        $query =array(
            "table" =>"FavoliteProductAssoc",
            "conditions" =>array(
                "id" =>$id,
            ),
        );
        $t =$this->select_one($query);

        return $t;
    }

    /**
     * 検索フォームの結果取得
     */
    public function get_by_search_form ($list_setting, $input, $is_forcsv=false)
    {
        // 条件を指定して要素を取得
        $query =$this->get_list_query($list_setting, $input);
        $query =$this->merge_query($query, array(
            "table" =>"FavoliteProductAssoc",
            "conditions" =>array(
            ),
        ));

        // CSV向けに取得
        if ($is_forcsv) {
            unset($query["offset"]);
            unset($query["limit"]);
            return $this->select_nofetch($query);
        }

        $ts =$this->select($query);
        $p =$this->select_pager($query);

        return array($ts,$p);
    }

    /**
     * フォームからのデータ更新/新規登録
     */
    public function save ($fields, $id=null)
    {
        // IDの指定があれば更新
        if ($id) {

            $query =array(
                "fields" =>$fields,
                "table" =>"FavoliteProductAssoc",
                "conditions" =>array(
                    "id" =>$id,
                ),
            );
            $this->update($query,$id);

        // IDの指定がなければ新規登録
        } else {

            $query =array(
                "fields" =>$fields,
                "table" =>"FavoliteProductAssoc",
            );
            $id =$this->insert($query);
        }

        return $id;
    }

    /**
     * 削除
     */
    public function drop ($id)
    {
        // 要素の削除
        $query =array(
            "table" =>"FavoliteProductAssoc",
            "conditions" =>array(
                "id" =>$id,
            ),
        );
        $this->delete($query,$id);
    }
}
