<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
ini_set( 'display_errors', '0' );

$cate = $_POST['cate'];
$html = "<option value=\"\">중분류를 선택하세요.</option>";
$query="select * from category where step=2 and pcode='".$cate."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
    $html.="<option value=\"".$rs->code."\">".$rs->name."</option>";
}

echo $html;

?>