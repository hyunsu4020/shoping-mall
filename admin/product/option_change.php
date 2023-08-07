<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
ini_set( 'display_errors', '0' );

$poid1 = $_POST['poid1'];
$poid2 = $_POST['poid2'];
$option_price1=0;
$option_price2=0;

$query="select * from product_options where poid='".$poid1."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();
$image_url = $rs->image_url;
$option_price1 = $rs->option_price;
$wcode=$poid1;

if($poid2){
    $query="select * from product_options where poid='".$poid2."'";
    $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
    $rs = $result->fetch_object();
    $option_price2 = $rs->option_price;
    $wcode=$poid1."_".$poid2;
}

    $query2="select cnt from wms where wcode='".$wcode."'";
    $result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
    $rs2 = $result2->fetch_object();
    $wmscnt = $rs2->cnt??0;

$data = array("image_url"=>$image_url,"option_price1"=>$option_price1,"option_price2"=>$option_price2,"cnt"=>$wmscnt);
echo json_encode($data);

?>
 

 
테이블에 들어 있는 값을 꺼내는 거니 넣을때보단 쉽다. 각 옵션에 맞춰 조합 후 확인하면 된다. 그리고 제품 리스트에서도 재고를 wms에서 가져오도록 수정하자.
 
/admin/product/product_list.php
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['AUID']){
  echo "<script>alert('권한이 없습니다.');history.back();</script>";
  exit;
}

$pageNumber  = $_GET['pageNumber']??1;//현재 페이지, 없으면 1
if($pageNumber < 1) $pageNumber = 1;
$pageCount  = $_GET['pageCount']??10;//페이지당 몇개씩 보여줄지, 없으면 10
$startLimit = ($pageNumber-1)*$pageCount;//쿼리의 limit 시작 부분
$firstPageNumber  = $_GET['firstPageNumber'];
$cate1  = $_GET['cate1'];
$cate2  = $_GET['cate2'];
$cate3  = $_GET['cate3'];
$ismain = $_GET["ismain"];
$isnew = $_GET["isnew"];
$isbest = $_GET["isbest"];
$isrecom = $_GET["isrecom"];
$sale_end_date=$_GET["sale_end_date"];
$search_keyword=$_GET["search_keyword"];

$query="select * from category where step=1";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
    $cate1array[]=$rs;
}

if($cate1){
    $query="select * from category where step=2 and pcode='".$cate1."'";
    $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
    while($rs = $result->fetch_object()){
        $cate2array[]=$rs;
    }
}

if($cate2){
    $query="select * from category where step=3 and pcode='".$cate2."'";
    $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
    while($rs = $result->fetch_object()){
        $cate3array[]=$rs;
    }
}


$scate=$cate1.$cate2.$cate3;//카테고리를 모두 묶음

if($scate){
    $search_where=" and cate like '".$scate."%'";//like 검색으로 검색
}

if($ismain){//값이 있는 경우에만 검색 조건에 추가한다.
    $search_where.=" and ismain=1";
}

if($isnew){
    $search_where.=" and isnew=1";
}

if($isbest){
    $search_where.=" and isbest=1";
}

if($isrecom){
    $search_where.=" and isrecom=1";
}

if($sale_end_date){
    $search_where.=" and sale_end_date<='".$sale_end_date."'";
}

if($search_keyword){
    $search_where.=" and (name like '%".$search_keyword."%' or content like '%".$search_keyword."%')";//like 검색으로 검색
}



$sql = "select *, (select sum(cnt) from wms w where w.pid=p.pid) as sumcnt from products p where 1=1";
$sql .= $search_where;
$order = " order by pid desc";//마지막에 등록한걸 먼저 보여줌
$limit = " limit $startLimit, $pageCount";
$query = $sql.$order.$limit;
//echo "query=>".$query."<br>";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
    $rsc[]=$rs;
}

//전체게시물 수 구하기
$sqlcnt = "select count(*) as cnt from products where 1=1";
$sqlcnt .= $search_where;
$countresult = $mysqli->query($sqlcnt) or die("query error => ".$mysqli->error);
$rscnt = $countresult->fetch_object();
$totalCount = $rscnt->cnt;//전체 갯수를 구한다.
$totalPage = ceil($totalCount/$pageCount);//전체 페이지를 구한다.

if($firstPageNumber < 1) $firstPageNumber = 1;
$lastPageNumber = $firstPageNumber + $pageCount - 1;//페이징 나오는 부분에서 레인지를 정한다.
if($lastPageNumber > $totalPage) $lastPageNumber = $totalPage;



  function isStatus($n){

        switch($n) {
           
            case -1:$rs="판매중지";
            break;
            case 0:$rs="대기";
            break;
            case 1:$rs="판매중";
            break;

        }

        return $rs;

    }

?>
<div style="text-align:center;"><h3>제품 리스트</h3></div>

    <form method="get" action="<?php echo $_SERVER["PHP_SELF"]?>">
        <div class="row g-3" style="padding-bottom:10px;">
            <div class="col-md-4">
                <select class="form-select" name="cate1" id="cate1" aria-label="Default select example">
                    <option value="">대분류</option>
                    <?php
                        foreach($cate1array as $c){
                    ?>
                        <option value="<?php echo $c->code;?>" <?php if($cate1==$c->code){echo "selected";}?>><?php echo $c->name;?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" name="cate2" id="cate2" aria-label="Default select example">
                    <option value="">중분류</option>
                    <?php
                        foreach($cate2array as $c){
                    ?>
                        <option value="<?php echo $c->code;?>" <?php if($cate2==$c->code){echo "selected";}?>><?php echo $c->name;?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" name="cate3" id="cate3" aria-label="Default select example">
                    <option value="">소분류</option>
                    <?php
                        foreach($cate3array as $c){
                    ?>
                        <option value="<?php echo $c->code;?>" <?php if($cate3==$c->code){echo "selected";}?>><?php echo $c->name;?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="input-group mb-12" style="width:100%;padding-bottom:10px;">
            <input class="form-check-input" type="checkbox" name="ismain" id="ismain" value="1" <?php if($ismain){echo "checked";}?>>메인&nbsp;
            <input class="form-check-input" type="checkbox" name="isnew" id="isnew" value="1" <?php if($isnew){echo "checked";}?>>신제품&nbsp;
            <input class="form-check-input" type="checkbox" name="isbest" id="isbest" value="1" <?php if($isbest){echo "checked";}?>>베스트&nbsp;
            <input class="form-check-input" type="checkbox" name="isrecom" id="isrecom" value="1" <?php if($isrecom){echo "checked";}?>>추천&nbsp;
            판매종료일:<input type="text" class="form-control" style="max-width:150px;" name="sale_end_date" id="sale_end_date" value="<?php echo $sale_end_date?>">&nbsp;
            <input type="text" class="form-control" name="search_keyword" id="search_keyword" placeholder="제목과 내용에서 검색합니다." value="<?php echo $search_keyword;?>" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="submit" id="search">검색</button>
        </div>
       
        </form>
        <form method="get" name="plist" action="plist_save.php">
        <div style="text-align:right;padding:10px;">  
            <button class="btn btn-primary" type="submit">변경내용저장</button>
        </div>
       
        <table class="table table-sm table-bordered">
          <thead>
          <tr style="text-align:center;">
            <th scope="col">사진</th>
            <th scope="col">제품명</th>
            <th scope="col">가격</th>
            <th scope="col">재고</th>
            <th scope="col">메인</th>
            <th scope="col">신제품</th>
            <th scope="col">베스트</th>
            <th scope="col">추천</th>
            <th scope="col">상태</th>
          </tr>
          </thead>
          <tbody>
          <?php
                foreach($rsc as $r){
            ?>
              <input type="hidden" name="pid[]" value="<?php echo $r->pid;?>">
          <tr>
            <th scope="row" style="width:100px;"><img src="<?php echo $r->thumbnail;?>" style="max-width:100px;"></th>
            <td><?php echo $r->name;?></td>
            <td style="text-align:right;"><s><?php echo number_format($r->price);?>원</s>
            <?php if($r->sale_price>0){?>
              <br>
              <?php echo number_format($r->sale_price);?>원
            <?php }?>
            </td>
            <td style="text-align:right;"><?php echo number_format($r->sumcnt);?>EA</td>
            <td style="text-align:center;"><input type="checkbox" name="ismain[<?php echo $r->pid;?>]" id="ismain_<?php echo $r->pid;?>" value="1" <?php if($r->ismain){echo "checked";}?>></td>
            <td style="text-align:center;"><input type="checkbox" name="isnew[<?php echo $r->pid;?>]" id="isnew_<?php echo $r->pid;?>" value="1" <?php if($r->isnew){echo "checked";}?>></td>
            <td style="text-align:center;"><input type="checkbox" name="isbest[<?php echo $r->pid;?>]" id="isbest_<?php echo $r->pid;?>" value="1" <?php if($r->isbest){echo "checked";}?>></td>
            <td style="text-align:center;"><input type="checkbox" name="isrecom[<?php echo $r->pid;?>]" id="isrecom_<?php echo $r->pid;?>" value="1" <?php if($r->isrecom){echo "checked";}?>></td>
            <td style="text-align:center;">
                <select class="form-select" style="max-width:120px;" name="stat[<?php echo $r->pid;?>]" id="stat" aria-label="Default select example">
                    <option value="-1" <?php if($r->status==-1){echo "selected";}?>>판매중지</option>
                    <option value="0" <?php if($r->status==0){echo "selected";}?>>대기</option>
                    <option value="1" <?php if($r->status==1){echo "selected";}?>>판매중</option>
                </select>
            </td>
            <td>
                <a href="product_view.php?pid=<?php echo $r->pid;?>"><button class="btn btn-primary" type="button">보기</button></a>
            </td>
          </tr>
          <?php }?>
          </tbody>
        </table>
        </form>
        <a href="product_up.php">
            <button class="btn btn-primary" type="button">제품등록</button>
        </a>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">        
<script>
    $(function(){
        $("#sale_end_date").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    $("#cate1").change(function(){
        var cate1 = $("#cate1 option:selected").val();
       
        var data = {
            cate1 : cate1
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'category2.php' ,
                data  : data ,
                dataType : 'html' ,
                error : function() {} ,
                success : function(return_data) {
                    $("#cate2").html(return_data);
                }
        });
    });

    $("#cate2").change(function(){
        var cate2 = $("#cate2 option:selected").val();
       
        var data = {
            cate2 : cate2
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'category3.php' ,
                data  : data ,
                dataType : 'html' ,
                error : function() {} ,
                success : function(return_data) {
                    $("#cate3").html(return_data);
                }
        });
    });  
</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>