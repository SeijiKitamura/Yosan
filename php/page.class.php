<?php
require_once("config.php");
require_once("db.class.php");

class PAGE extends DB{
 public $pagename;
 public $items;
 
 function __construct(){
  parent::__construct();
 }//__construct

//-------------------------------------------------------//
// 単一のページ詳細を返す                                //
//-------------------------------------------------------//
 public function getPage($page){
  $this->items=null;
  //attrをゲット
  $this->select ="attr";
  $this->from =TABLE_PREFIX.PAGECONF;
  $this->group =$this->select;
  $this->getArray();
  $attr=$this->ary;

  if(! $attr) throw new exception($page."のページデータがありません");
  $this->items=null;
  $this->select =" t.pagename";
  foreach($attr as $rowcnt =>$rowdata){
   if(DBENGIN=="mysql"){
    $this->select.=",max(case when t.attr='".$rowdata["attr"]."' then t.val else '' end ) as `".$rowdata["attr"]."`";
   }
   if(DBENGIN=="postgres"){
    $this->select.=",max(case when t.attr='".$rowdata["attr"]."' then t.val else '' end ) as \"".$rowdata["attr"]."\"";
   }
  }//foreach
  $this->from =TABLE_PREFIX.PAGECONF." as t ";
  $this->where=" t.pagename='".$page."'";
  $this->group=" t.pagename";
  $this->getArray();
  $this->items=$this->ary;
 }// public function getPage($page){

//-------------------------------------------------------//
// グループごとのページ詳細を返す                        //
//-------------------------------------------------------//
 public function getGroup($flg0){
  $this->items=null;
  //attrをゲット
  $this->select ="attr";
  $this->from =TABLE_PREFIX.PAGECONF;
  $this->group =$this->select;
  $this->getArray();
  $attr=$this->ary;

  $this->items=null;
  $this->select =" t.pagename";
  foreach($attr as $rowcnt =>$rowdata){
   $this->select.=",max(case when t.attr='".$rowdata["attr"]."' then t.val else '' end ) as `".$rowdata["attr"]."`";
  }//foreach
  $this->from =TABLE_PREFIX.PAGECONF." as t ";
  $this->from.="inner join (";
  $this->from.=" select pagename,val from ".TABLE_PREFIX.PAGECONF;
  $this->from.=" where attr='flg0' and val='".$flg0."'";
  $this->from.=" group by pagename,val) as t1 on";
  $this->from.=" t.pagename=t1.pagename";
  $this->group=" t.pagename";
  $this->order=" case when t.attr='flg1' then t.val else 999999 end";
  $this->getArray();
  $this->items=$this->ary;
 }// public function getGroup($flg0){

 public function getMenuData(){
  $this->items=null;

  $this->select =" pagename";
  $this->select.=",max(case when attr='title' then val else '' end) as title";
  $this->select.=",max(case when attr='flg0' then val else '' end) as menu";
  $this->select.=",max(case when attr='flg1' then val else '' end) as grp";
  $this->from =TABLE_PREFIX.PAGECONF;
  $this->where="attr='title' or attr='flg0' or attr='flg1'";
  $this->group="pagename";
  $this->order =" max(case when attr='flg0' then val else '' end)";
  $this->order.=",max(case when attr='flg1' then val else '' end)";
  if(! $this->items=$this->getArray()) return false;
  
  $menu=null;
  foreach($this->items as $rownum=>$row){
   if($menu!=$row["menu"]){
    $menu=$row["menu"];
    $data[$menu]=$row;
   }
   else{
    $data[$row["menu"]]["group"][]=$row;
   }
  }
  $this->items=$data;
 }// public function getMenu()
}//class page
?>
