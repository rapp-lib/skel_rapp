<?php
namespace R\App\Service\App;
use R\Lib\Table\Feature\BaseFeatureProvider;

class AppTableFeatureProvider extends BaseFeatureProvider
{
    protected function chain_setIgnoreAcceptFlg ($query, $flg)
    {
        $query->setAttr("ignore_accept_flg", $flg);
    }
    
    /**
     * @hook on_read
     * ユーザ表示項目を関連付ける
     */
    protected function on_read_colAcceptFlg ($query, $col_name)
    {
        if ( ! app()->user->getCurrentPriv("admin") && ! $query->getAttr("ignore_accept_flg")) {
            // if (! $controller == "user_register" && ($query->getDef()->getDefTableName() == "User" || $query->getDef()->getDefTableName() == "UserProduct")) {
                $query->where($query->getTableName().".".$col_name, "2");
            // }
        }
    }

    /**
     * @hook on_read
     * ユーザ表示項目を関連付ける
     */
    protected function on_read_colDisplayStatus ($query, $col_name)
    {
        if (! app()->user->getCurrentPriv("admin")) {
            $query->where($query->getTableName().".".$col_name, "1");
        }
    }

    /**
     * @hook on_read
     * ユーザ表示項目を関連付ける
     */
    protected function on_read_colReleaseDate ($query, $col_name)
    {
        if (! app()->user->getCurrentPriv("admin")) {
            if ($query->getDef()->getDefTableName() == "ProductFile" && $query->getJoinByName("CommonFile")) {
                $query->where("(ProductFile.release_date <= CURRENT_DATE OR CommonFile.release_date <= CURRENT_DATE)");
            } else {
                $query->where($query->getTableName().".".$col_name." <= CURRENT_DATE");
            }
        }
    }

    public function alias_productFiles ($result, $src_values, $alias)
    {
        if (count($src_values) === 0) return array();
        // 製品に紐づく関連ファイルを取得
        $entries = table("ProductFile")->findBy(array("product_id"=>$src_values))->select();

        foreach($entries as $k => $v) {
            $dest_values[$v["product_id"]][$v["id"]] = $v;
        }
        return $dest_values;
    }
    
    /**
     * @alias
     * 製品名と型名を括弧付きで結合
     */
    public function alias_addBracket($result, $src_values, $alias)
    {
        $hashed_list = $result->getHashedBy("id",$alias["first_col"],$alias["second_col"]);
        foreach ($hashed_list as $k=>$v) {
            foreach ($v as $k2 => $v2) {
                if ($v2) $v2="（".$v2."）";
                $str =$k2.$v2;
                $result_list[] =$str;
            }
        }
        return $result_list;
    }

    public function chain_setPersonalDelete ($query, $id)
    {
        $query->setValues(array_fill_keys(table("User")->getColNamesByAttr("personal_data"),""));
        $query->setValues(array_fill_keys(table("User")->getColNamesByAttr("erase_flg"),"2"));
        $query->setValues(array_fill_keys(table("User")->getColNamesByAttr("erase_date"),date("Y-m-d H:i:s")));
        $query->setValues(array(table("User")->getIdColName() =>$id));
    }

    public function result_getCsvdlList ($result)
    {
        // 週ごとにDLフラグと抹消フラグをチェックする
        foreach ($result as $v) {
            
            $y =date("Y",strtotime($v["accept_date"]));
            $week =date("W",strtotime($v["accept_date"]));
            $w =date("w",strtotime($v["accept_date"]));
            $cur =$y.$week;
            if (! $week_list[$cur]){
                $daysLeft = 6 - $w;
                $week_list[$cur] = array(
                    "start_date" =>date("Y/m/d", strtotime("-{$w} day", strtotime($v["accept_date"]))),
                    "end_date" =>date('Y/m/d', strtotime("+{$daysLeft} day", strtotime($v["accept_date"]))),
                    "download_flg" =>"2",
                    "erase_flg" =>"2",
                );
            }
            if ($v["download_flg"] == "1") $week_list[$cur]["download_flg"] = "1";
            if ($v["erase_flg"] == "1") $week_list[$cur]["erase_flg"] = "1";
        }
        krsort($week_list);
        return $week_list;
    }
}
