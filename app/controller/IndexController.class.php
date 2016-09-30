<?php
namespace {

/**
 * @controller
 */
class IndexController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = "customer";
    protected $priv_required = false;

    /**
     * @page
     * @title トップページ
     */
    public function act_index ()
    {
        $t = table("Customer")
            ->findById(1)
            ->selectOne();
        $t = table("Customer")
            ->findById(1)
            ->value("name","test")
            ->insert(array("name"=>"test2"));
        $t = table("Customer")
            ->save(3,array("name"=>"test3"));
        $t = table("Customer")
            ->findMine()
            ->selectOne();
        $t = table("Customer")
            ->findByAssoc("Customer",$customer_id)
            ->selectOne();
        list($ts,$p) = table("Customer")
            ->findBySearchForm($this->list_setting, $input)
            ->selectPagenate();
    }
}

}
namespace R\App\Table {
use R\Lib\Query\Table_Base;

class Table_App extends Table_Base
{
}

}
namespace R\App\Table {

class CustomerTable extends Table_App
{
    protected $cols = array(
        "name" => array("type"=>"string", "comment"=>"氏名"),

        "id" => array("type"=>"integer", "id"=>true, "comment"=>"ID"),
        "reg_date" => array("type"=>"datetime", "comment"=>"作成日時"),
        "del_flg" => array("del_flg"=>true, "type"=>"integer", "comment"=>"削除フラグ"),

        "imgs" => array(
            "type"=>"assoc",
            "table"=>"MemberImg",
        ),
        "categories" => array(
            "type"=>"assoc",
            "table"=>"MemberCategoryAssoc",
            "reduce_by"=>"category_id",
        ),
        "detail" => array(
            "type"=>"assoc",
            "join"=>true,
            "table"=>"MemberDetail",
        ),
        "incharge_staff" => array(
            "type"=>"assoc",
            "join"=>"LEFT",
            "table"=>"Staff",
            "col_name" =>"incharge_staff_id",
        ),
    );
    protected $fkeys = array(
        "Customer" => "id",
        "StaffRole" => "incharge_staff_id",
        "Staff" => "incharge_staff_id",
    );

    public function chain_findByFriendIds ($friend_ids)
    {
        $ids = table("Member")
            ->where(array("friend_id"=>$friend_ids))
            ->selectHash("id");
        $this->query->where(array("id"=>$ids));
    }
}

}