<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
ini_set( 'display_errors', '0' );

if(!$_SESSION['AUID']){
    echo "<script>alert('권한이 없습니다.');history.back();</script>";
    exit;
}


$cate=$_POST["cate1"].$_POST["cate2"].$_POST["cate3"];//대중소분류를 모두 저장한다.
$name=$_POST["name"];//제품명
$delivery_fee=$_POST["delivery_fee"];//택배비
$price=$_POST["price"];//가격
$sale_price=$_POST["sale_price"];//세일가
$sale_ratio=$_POST["sale_ratio"];//세일비율
$cnt=$_POST["cnt"]??0;//재고
$contents=rawurldecode($_POST['contents']);//제품 설명
$ismain=$_POST["ismain"];//메인
$isnew=$_POST["isnew"];//신상품
$isbest=$_POST["isbest"];//베스트
$isrecom=$_POST["isrecom"];//추천
$locate=$_POST["locate"];//위치
$sale_end_date=$_POST["sale_end_date"];//판매종료일
$file_table_id=$_POST["file_table_id"];//이미지
$file_table_id=rtrim($file_table_id,",");//오른쪽 끝에 , 삭제
$optionCate1=$_POST["optionCate1"];//옵션분류
$optionCate2=$_POST["optionCate2"];//옵션분류
$wms=$_REQUEST["wms"];//재고


if($_FILES["thumbnail"]["name"]){//첨부한 파일이 있으면

        if($_FILES['thumbnail']['size']>10240000){//10메가
            echo "<script>alert('10메가 이하만 첨부할 수 있습니다.');history.back();</script>";
            exit;
        }

        if($_FILES['thumbnail']['type']!='image/jpeg' and $_FILES['thumbnail']['type']!='image/gif' and $_FILES['thumbnail']['type']!='image/png'){//이미지가 아니면, 다른 type은 and로 추가
            echo "<script>alert('이미지만 첨부할 수 있습니다.');history.back();</script>";
            exit;
        }

        $save_dir = $_SERVER['DOCUMENT_ROOT']."/pdata/";//파일을 업로드할 디렉토리
        $filename = $_FILES["thumbnail"]["name"];
        $ext = pathinfo($filename,PATHINFO_EXTENSION);//확장자 구하기
        $newfilename = date("YmdHis").substr(rand(),0,6);
        $thumbnail = $newfilename.".".$ext;//새로운 파일이름과 확장자를 합친다
       
        if(move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $save_dir.$thumbnail)){
            $thumbnail = $_CONFIG["CDN_SERVER"]."/pdata/".$thumbnail;
        }else{
            echo "<script>alert('이미지를 등록할 수 없습니다. 관리자에게 문의해주십시오.');history.back();</script>";
            exit;
        }

}

$mysqli->autocommit(FALSE);//커밋이 안되도록 지정

try {

    $sale_cnt = 0;//판매량
    $query="INSERT INTO products
    (name, cate, content, thumbnail, price, sale_price, sale_ratio, cnt, sale_cnt, isnew, isbest, isrecom, ismain, locate, userid, sale_end_date, reg_date, delivery_fee)
    VALUES('$name'
    , '".$cate."'
    , '".$contents."'
    , '".$thumbnail."'
    , '".$price."'
    , '".$sale_price."'
    , '".$sale_ratio."'
    , ".$cnt."
    , ".$sale_cnt."
    , '".$isnew."'
    , '".$isbest."'
    , '".$isrecom."'
    , '".$ismain."'
    , '".$locate."'
    , '".$_SESSION['AUID']."'
    , '".$sale_end_date."'
    , now()
    , '".$delivery_fee."'
    )";

    $rs=$mysqli->query($query) or die($mysqli->error);
    $pid = $mysqli -> insert_id;

    //옵션부분
    $optionName1=$_REQUEST["optionName1"];//옵션명
    $optionCnt1=$_REQUEST["optionCnt1"];//재고
    $optionPrice1=$_REQUEST["optionPrice1"];//가격

    $optionName2=$_REQUEST["optionName2"];//옵션명
    $optionCnt2=$_REQUEST["optionCnt2"];//재고
    $optionPrice2=$_REQUEST["optionPrice2"];//가격

    if($_FILES["optionImage1"]["name"][0]){//첨부한 파일이 있으면

        for($k=0;$k<count($_FILES["optionImage1"]["name"]);$k++){

            if($_FILES['optionImage1']['size'][$k]>10240000){//10메가
                echo "<script>alert('10메가 이하만 첨부할 수 있습니다.');history.back();</script>";
                exit;
            }

            if($_FILES['optionImage1']['type'][$k]!='image/jpeg' and $_FILES['optionImage1']['type'][$k]!='image/gif' and $_FILES['optionImage1']['type'][$k]!='image/png'){//이미지가 아니면, 다른 type은 and로 추가
                echo "<script>alert('이미지만 첨부할 수 있습니다.');history.back();</script>";
                exit;
            }

            $save_dir = $_SERVER['DOCUMENT_ROOT']."/pdata/optiondata/";//파일을 업로드할 디렉토리
            $filename = $_FILES["optionImage1"]["name"][$k];
            $ext = pathinfo($filename,PATHINFO_EXTENSION);//확장자 구하기
            $newfilename = date("YmdHis").substr(rand(),0,6);
            $optionImage1 = $newfilename.".".$ext;//새로운 파일이름과 확장자를 합친다
           
            if(move_uploaded_file($_FILES["optionImage1"]["tmp_name"][$k], $save_dir.$optionImage1)){
                $upload_option_image[]=$_CONFIG["CDN_SERVER"]."/pdata/optiondata/".$optionImage1;
            }

        }

    }

    $k=0;
    foreach($optionName1 as $on){

        if($on){
            $optQuery="INSERT INTO testdb.product_options
            (pid, cate, option_name, option_price, image_url)
            VALUES (".$pid.", '".$optionCate1."', '".$on."', ".$optionPrice1[$k].", '".$upload_option_image[$k]."')";
            $ofs=$mysqli->query($optQuery) or die($mysqli->error);
            $poid=$mysqli->insert_id;
            $op1[]=$poid;
            $k++;
        }
    }

    $k=0;
    foreach($optionName2 as $on){

        if($on){
            $optQuery="INSERT INTO testdb.product_options
            (pid, cate, option_name, option_price)
            VALUES (".$pid.", '".$optionCate2."', '".$on."', ".$optionPrice2[$k].")";
            $ofs=$mysqli->query($optQuery) or die($mysqli->error);
            $poid=$mysqli->insert_id;
            $op2[]=$poid;
            $k++;
        }
    }

    $j=0;

        if($op1 && $op2){
            foreach($op1 as $c1){
                foreach($op2 as $c2){
                    $wcode=$c1."_".$c2;
                    $wmsQuery="INSERT INTO testdb.wms
                    (pid, wcode, cnt)
                    VALUES (".$pid.",'".$wcode."',".$wms[$j].")";
                    $mysqli->query($wmsQuery) or die($mysqli->error);
                    $j++;
                }
            }
        }else if($op1 && !$op2){
            foreach($op1 as $c1){
                    $wcode=$c1;
                    $wmsQuery="INSERT INTO testdb.wms
                    (pid, wcode, cnt)
                    VALUES (".$pid.",'".$wcode."',".$wms[$j].")";
                    $mysqli->query($wmsQuery) or die($mysqli->error);
                    $j++;
            }
        }else if(!$op1 && $op2){
            foreach($op2 as $c2){
                    $wcode=$c2;
                    $wmsQuery="INSERT INTO testdb.wms
                    (pid, wcode, cnt)
                    VALUES (".$pid.",'".$wcode."',".$wms[$j].")";
                    $mysqli->query($wmsQuery) or die($mysqli->error);
                    $j++;
            }
        }else if(!$op1 && !$op2){
                    $wmsQuery="INSERT INTO testdb.wms
                    (pid, wcode, cnt)
                    VALUES (".$pid.",'".$wcode."',".$wms[0].")";
                    $mysqli->query($wmsQuery) or die($mysqli->error);
        }

   

    if($file_table_id){//첨부한 이미지 테이블 업데이트
        $upquery="update product_image_table set pid=".$pid." where imgid in (".$file_table_id.")";
        $fs=$mysqli->query($upquery) or die($mysqli->error);
    }

    $mysqli->commit();//디비에 커밋한다.

    echo "<script>alert('등록했습니다.');location.href='/admin/product/product_list.php';</script>";
    exit;

}catch (Exception $e) {

    $mysqli->rollback();//저장한 테이블이 있다면 롤백한다.

    echo "<script>alert('등록하지 못했습니다. 관리자에게 문의해주십시오.');history.back();</script>";
    exit;

}

?>