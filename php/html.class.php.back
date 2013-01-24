<?php
//----------------------------------------------------------//
//  html.class.php 
//  html生成用クラス
//----------------------------------------------------------//
//メソッド一覧
//----------------------------------------------------------//
// setpagelink($data,$page)  ページリンク用(<ul>を含むhtmlを返す)
// setfooter($page)          フッター用($pageは表示するページを代入)
//----------------------------------------------------------//

class html{
//----------------------------------------------------------//
// head
//----------------------------------------------------------//
 private static function head(){
  $html=<<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
 <head>
  <meta name="ROBOTS" content="__index__">
  <meta name="ROBOTS" content="__follow__">
  <meta http-equiv="Content-language" content="ja">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Script-Type" content="text/javascript">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="__CACHEDATE__">
  <meta name="description" content="__description__">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=2,user-scalable=yes">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" href="__FAV__" type="type/ico" sizes="16x16" /> 
  <link rel="stylesheet" href="__CSS____css__" media="screen"/> 
  <link rel="stylesheet" href="__CSS__print.css" media="print"/> 
  <link rel="next" href="__next__" />
  <link rel="prev" href="__prev__"/> 
  
  <title> __title__ | __CORPNAME__ </title>
  
  <script type="text/javascript" src="__JQUERY__"></script>
 </head>

EOF;
  return $html;
 }//private static function head(){

//----------------------------------------------------------//
// head用($dataは単一データとする）
//----------------------------------------------------------//
 public static function sethead($data){
  $html=self::head();
  foreach($data as $col=>$val){
   $pattern="__".$col."__";
   $replace=$val;
   $html=str_replace($pattern,$val,$html);
  }//foreach

  //CSSディレクトリをセット
  $pattern="__CSS__";
  $val=CSS;//config.php
  $html=str_replace($pattern,$val,$html);

  //ファビコンをセット
  $pattern="__FAV__";
  $val=FAV;//config.php
  $html=str_replace($pattern,$val,$html);

  //Javascriptをセット
  $pattern="__JQUERY__";
  $val=JQ;//config.php
  $html=str_replace($pattern,$val,$html);

  //会社名をセット
  $pattern="__CORPNAME__";
  $val=CORPNAME;//config.php
  $html=str_replace($pattern,$val,$html);

  //キャッシュ有効日をセット
  $pattern="__CACHEDATE__";
  $val=gmdate("D,d M Y H:i:s",strtotime("1day"))." GMT";
  $html=str_replace($pattern,$val,$html);

  if(is_mobile()){
   $pattern="/\.css/";
   $replace=".smart.css";
   $html=preg_replace($pattern,$replace,$html);
  }
  return $html;
 }//public static function sethead($page){

//----------------------------------------------------------//
// ヘッダー雛形
//----------------------------------------------------------//
 private static function header_tmp(){
  $html=<<<EOF
 <body>
  <div id="wrapper">
<!-- -------------- div header start ---------------------- -->
   <div id="header">

<!-- -------------- div logo   start ---------------------- -->
    <div class="logo">
     <a href="index.php">
      <img src="__LOGO__" alt="__CORPNAME__">
     </a>
    </div>
<!-- -------------- div logo   end   ---------------------- -->

<!-- -------------- div header end   ---------------------- -->
EOF;
  return $html;
 }//private static function header_tmp(){

//----------------------------------------------------------//
// ヘッダー出力
//----------------------------------------------------------//
 public static function setheader($base,$topgrp,$centergrp){
  $html=self::header_tmp();
  //ロゴをセット
  $pattern="__LOGO__";
  $replace=LOGO;
  $html=str_replace($pattern,$replace,$html);

  //ロゴメッセージをセット
  $pattern="__CORPNAME__";
  $replace=CORPNAME;
  $html=str_replace($pattern,$replace,$html);

  //トップグループをセット
  $pattern="__MININAVI__";
  $replace=self::setpagelink($topgrp,$base);
  $html=str_replace($pattern,$replace,$html);

  //センターグループをセット
  $pattern="__TIMESALE__";
  $replace=self::setpagelink($centergrp,$base);
  $html=str_replace($pattern,$replace,$html);

  return $html;
 }

//----------------------------------------------------------//
// フッター
//----------------------------------------------------------//
private static function footer(){
 $html=<<<EOF
   <div class="clr"></div>
   <div id="footer">
    <div class="footerlink">__LINK__</div>
   </div>
  </div>
 </body>
</html>
EOF;
 return $html;
}

//----------------------------------------------------------//
// フッター用
//----------------------------------------------------------//
 public static function setfooter($base,$topgrp,$centergrp){
  $html=self::footer();
  $html=str_replace("__LINK__",$replace,$html);
  return $html;
 }

//----------------------------------------------------------//
// ページリンク用(<ul>を含むhtmlを返す)
//----------------------------------------------------------//
 public static function setpagelink($data,$page=null){
  if (! is_array($data)) return false;
  $html ="<ul>\n";
  foreach($data as $rownum=>$rowdata){
   $html.="<li>";

   if($rowdata["url"] && $rowdata["url"]!=$page){
    $html.="<a href='".$rowdata["url"]."'>";
   }//if

   $html.=$rowdata["title"];

   if($rowdata["url"] && $rowdata["url"]!=$page){
    $html.="</a>";
   }//if

   $html.="</li>\n";
  }//foreach
  $html.="</ul>\n";
  return $html;
 }//public static function setlink($data,$page=null){
//----------------------------------------------------------//
// グループ用
//----------------------------------------------------------//
 public static function group(){
$html=<<<EOF
 <li>
  <a href='__LINK__' target='_blank'>
   __GROUP__
  </a>
 </li>
EOF;
  return $html;
 }// public static function group(){
}//class html{

function is_mobile () {
 $useragents = array(
 'iPhone', // Apple iPhone
 'iPod', // Apple iPod touch
 'Android', // 1.5+ Android
 'dream', // Pre 1.5 Android
 'CUPCAKE', // 1.5+ Android
 'blackberry9500', // Storm
 'blackberry9530', // Storm
 'blackberry9520', // Storm v2
 'blackberry9550', // Storm v2
 'blackberry9800', // Torch
 'webOS', // Palm Pre Experimental
 'incognito', // Other iPhone browser
 'webmate' // Other iPhone browser
 );
 $pattern = '/'.implode('|', $useragents).'/i';
 return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

?>
