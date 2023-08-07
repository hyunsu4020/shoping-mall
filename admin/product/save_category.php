<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
ini_set( 'display_errors', '0' );

if(!$_SESSION['AUID']){
    $retun_data = array("result"=>"member");
    echo json_encode($retun_data);
    exit;
}

$name = $_POST['name'];
$code = $_POST['code'];
$pcode = $_POST['pcode'];
$step = $_POST['step'];

//코드와 분류명을 사용하고 있는지 확인
$result = $mysqli->query("select cid from category where step=".$step." and (name='".$name1."' or code='".$code1."')") or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->cid){
    $retun_data = array("result"=>"-1");
    echo json_encode($retun_data);
    exit;
}

$sql="INSERT INTO category
(code, pcode, name, step)
VALUES('".$code."', '".$pcode."', '".$name."', ".$step.")";
$result=$mysqli->query($sql) or die($mysqli->error);
if($result){
    $retun_data = array("result"=>1);
    echo json_encode($retun_data);
}else{
    $retun_data = array("result"=>0);
    echo json_encode($retun_data);
}

?>