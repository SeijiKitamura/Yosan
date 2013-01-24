<?php 
require_once("page.class.php");

class HTML extends PAGE{
 public $page;
 public $items;
 
 public $tmphtml;

 function __construct(){
  parent::__construct();
 }//function __construct()

 //------------------------------------------------------//
 // HEADを生成するメソッド
 //------------------------------------------------------//
 public function getHead(){
  if(! $this->page) throw new exception("ページを指定してください");

  //ページ情報取得
  $this->getPage($this->page);

  //HTMLゲット
  $this->tmphtml=file_get_contents(RDIR.PHP."/head_temp.html");
  
  //値をセット
  foreach($this->items[0] as $colname=>$val){
   $this->tmphtml=str_replace("__".$colname."__",$val,$this->tmphtml);
  }//foreach($this->items as $colname=>$val)

  //固定値を変換
  $this->tmphtml=str_replace("__CSS__"      ,CSS,$this->tmphtml);
  $this->tmphtml=str_replace("__FAV__"      ,FAV,$this->tmphtml);
  $this->tmphtml=str_replace("__JQUERY__"   ,JQNAME,$this->tmphtml);
  $this->tmphtml=str_replace("__BLOGTITLE__",BLOGTITLE,$this->tmphtml);
  $cachedate=gmdate("D,d M Y H:i:s",strtotime("1day"))." GMT";
  $this->tmphtml=str_replace("__CACHEDATE__",$cachedate,$this->tmphtml);

  //headに追加したい場合、<!-- head_end -->をstr_replaceする。
  //その際、<!-- head_end -->を最後に付け加えること。
 }//public function getHead()

 //------------------------------------------------------//
 // Footerを生成するメソッド
 //------------------------------------------------------//
 public function getFooter(){
  $this->tmphtml=null;

  //HTMLゲット
  $this->tmphtml=file_get_contents(RDIR.PHP."/footer_temp.html");
 }

 //------------------------------------------------------//
 // ul liを生成するメソッド
 //------------------------------------------------------//
 // 実行内容:$this->tmphtmlにHTML(ul)を生成する
 //------------------------------------------------------//
 // $this->itemsに以下のデータがセットされていることが前提
 // [n]([pagename],
 //     [title],
 //     [group][n]([pagename],
 //                [title]
 //               )
 //    )
 //------------------------------------------------------//
 public function ul($me=null){
  //メンバ確認
  if(! $this->items) throw new exception("データがありません");

  $menu=null;
  $li="";
  $this->tmphtml.="<ul>\n";

  foreach($this->items as $rownum=>$row){
   if($me==$row["pagename"]){
    $li="<li>".$row["title"]."</li>\n";
   }
   else{
   $li="<li><a href='".$row["pagename"]."'>".$row["title"]."</a></li>\n";
   }
   $this->tmphtml.=$li;

   if(count($row["group"])) $this->tmphtml.="<ul>\n";
   foreach($row["group"] as $rownum2=>$row2){
    if($me==$row2["pagename"]){
     $li="<li>".$row2["title"]."</li>\n";
    }
    else{
     $li="<li><a href='".$row2["pagename"]."'>".$row2["title"]."</a></li>\n";
    }
    $this->tmphtml.=$li;
   }// foreach($row["group"] as $rownum2=>$row2)
   if(count($row["group"])) $this->tmphtml.="</ul>\n";
  }// foreach($this->items as $rownum=>$row)

  $this->tmphtml.="</ul>\n";
 }//private function ul()


//-------------------------------------------------------//
// 総合メニューをゲット                                   //
//-------------------------------------------------------//
 public function getMenu($me){
  $this->getMenuData(); //page.class.php
  $this->tmphtml="<div class='menu_div'>\n";
  $this->ul($me);
  $this->tmphtml.="</div>\n";
 }//public function getMenu($me)

 public function getLogo(){
  $this->tmphtml="<div class='logo_div'>\n";
  $this->tmphtml.="<img src='".LOGO."' alt='".BLOGTITLE."'>\n";
  $this->tmphtml.="</div>\n";
 }//public function getLogo(){
}
?>
