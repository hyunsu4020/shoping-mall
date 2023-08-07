<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
ini_set( 'display_errors', '0' );

$cate = $_POST['cate'];
$step = $_POST['step'];
if($step==2){
    $html = "<option value=\"\">중분류</option>";
}else if($step==3){
    $html = "<option value=\"\">소분류</option>";
}
$query="select * from category where step=".$step." and pcode='".$cate."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
    $html.="<option value=\"".$rs->code."\">".$rs->name."</option>";
}

echo $html;

?>