<?php
require_once("config.php");
require_once("db.class.php");
require_once("function.php");
class ImportData extends db{
 public $table;       //更新したいテーブル名
 public $items;       //CSVデータ

 private $csvfilepath; //CSVファイルパス
 private $tablename;   //テーブル名
 private $csvcol;      //CSV列情報
 private $csvdata;     //CSVデータ
 private $tablecol;    //テーブル列情報

 function __construct(){
  parent::__construct();
 }//function __construct(){
 
//---------------------------------------------------------//
// CSVファイルを変数へ格納
// $this->items["data"][データ数][列名][値]
// $this->items["data"][データ数][status][true|false]
// $this->items["data"][データ数][err][エラー内容]
// $this->csvdata[データ数][列番号][値]
//---------------------------------------------------------//
 public function getData(){
  //メンバセット
  $this->csvfilepath=RDIR.DATA."/".$this->table.".csv";
  $this->csvcol     =$GLOBALS["CSVCOL"][$this->table];
  $this->tablename  =TABLE_PREFIX.$this->table;
  $this->tablecol   =$GLOBALS["TABLES"][$this->table];
  $this->items=null;
  $this->csvdata=null;

  //エラーチェック
  if(! file_exists($this->csvfilepath)){
   throw new exception($this->csvfilepath."が存在しません");
  }//if

  if(! $this->csvcol){
   throw new exception($this->csvcol.":CSV列情報が存在しません");
  }//if

  if(! $this->tablecol){
   throw new exception($this->tablecol.":テーブル列情報が存在しません");
  }//if

  //CSVファイル読み込み($csvdataへ格納)
  if(! $fl=fopen($this->csvfilepath,"r")){
   throw new exception ($this->csvfilepath.":ファイルが開けません");
  }//if

  while($line=fgets($fl)){
   $line=str_replace("\n","",$line);
   $line=str_replace("\r","",$line);
   $line=mb_convert_encoding($line,"UTF-8",CSVCHARSET);
   $this->csvdata[]=explode(",",$line);
  }//while

  if(! $this->csvdata){
   throw new exception ($this->csvfilepath.":データが空ですよ...");
  }
  //列情報をもとに$this->itemsへ格納
  foreach($this->csvdata as $rownum=>$rowdata){
   foreach($rowdata as $colnum=>$val){
    $colname=$this->csvcol[$colnum];
    $this->items["data"][$rownum][$colname]=$val;
   }//foreach
  }//foreach

  //データ整合性チェック($this->items[i]["err"]へ格納)
  foreach($this->items["data"] as $rownum =>$rowdata){
   foreach($rowdata as $col=>$val){
    $msg=null;
    if(! CHKTYPE($this->tablecol[$col]["type"],$val)){
     $msg=$this->tablecol[$col]["local"]."の値が不正です";
    }//if

    if(! $msg) $this->items["data"][$rownum]["status"]=true;
    else{
     $this->items["data"][$rownum]["status"]=false;
     $this->items["data"][$rownum]["err"]=$msg;
    }//else
   }//foreach
  }//foreach
 }//function getData(){

//---------------------------------------------------------//
// ページ情報を更新
// 更新方法:該当データを全削除後、CSVデータを登録
//---------------------------------------------------------//
 public function setPageConf(){
  $this->table=PAGECONF;

  //データゲット
  $this->getData();
  
  try{
   //トランザクション開始
   $this->BeginTran();

   //データ削除
   $this->from=$this->tablename;
   $this->where="id>0";
   $this->delete();

   //データ更新
   foreach($this->items["data"] as $rownum=>$rowdata){
    if (! $rowdata["status"]) continue;  //エラーデータを除く
    foreach($rowdata as $col=>$val){
     if($col=="status") continue;
     //echo $col." ".$val."\n";
     $this->updatecol[$col]=$val;
    }//foreach
    $this->from=$this->tablename;
    $this->where="id=0";
    $this->update();
   }//foreach
   $this->Commit();
  }//try
  catch(Exception $e){
   $this->RollBack();
   throw $e;
  }//catch
 }// public function setPageConf(){
}//class IMPORTDATA extends db{

?>
