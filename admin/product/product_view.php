<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['AUID']){
    echo "<script>alert('권한이 없습니다.');history.back();</script>";
    exit;
}

//ini_set( 'display_errors', '1' );

$pid = $_GET['pid'];

$query="select *, (select sum(cnt) from wms w where w.pid=p.pid) as sumcnt from products p where pid=".$pid;
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();


$query2="select * from product_options where cate='컬러' and pid=".$pid;
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
while($rs2 = $result2->fetch_object()){
    $options1[]=$rs2;
}

$query2="select * from product_options where cate='사이즈' and pid=".$pid;
$result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
while($rs2 = $result2->fetch_object()){
    $options2[]=$rs2;
}

?>
<style>
    .col{
        border: 1px solid #f1f1f1;
    }

    [type=radio] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    [type=radio] + span {
        cursor: pointer;
    }
    [type=radio]:checked + span {
        outline: 5px solid indigo;
    }
</style>
<link  rel="stylesheet"  href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<div class="container">
  <div class="row">
    <div class="col" style="text-align:center;">
      <img id="pimg" src="<?php echo $rs->thumbnail;?>" style="max-width:200px;">
    </div>
    <div class="col">
      <h3><?php echo $rs->name;?></h3>
        <div>
            가격 : <span id="price"><?php echo number_format($rs->price);?></span>원<br>
            재고 : <span id="cnt"><?php echo number_format($rs->sumcnt);?></span>EA
        </div>
        <?php if($options1){?>
        <!-- <div>
            옵션 :
            <select name="poption" id="poption">
                <option value="">선택하세요</option>
                <?php foreach($options as $op){?>
                    <option value="<?php echo $op->poid;?>"><?php echo $op->option_name;?></option>
                <?php }?>
            </select>
        </div> -->
        <br>
        <div>
            <?php foreach($options1 as $op1){?>
                <input type="radio" name="poption1" id="poption1_<?php echo $op1->poid;?>" value="<?php echo $op1->poid;?>">
                    <span  onclick="jQuery('#poption1_<?php echo $op1->poid;?>').click();" style="content:url(<?php echo $op1->image_url;?>);height:100px;width:100px;"></span>
                </input>
            <?php }?>
           
        </div>
        <br>
        <div>
            <?php foreach($options2 as $op2){
                $option_name=$op2->option_name;
                if($op2->option_price)$option_name.="(+".number_format($op2->option_price).")";
                ?>
                <input type="radio" name="poption2" id="poption2_<?php echo $op2->poid;?>" value="<?php echo $op2->poid;?>">
                    <span  onclick="jQuery('#poption2_<?php echo $op2->poid;?>').click();"><?php echo $option_name;?></span>
                </input>
            <?php }?>
           
        </div>
        <?php }?>
    </div>
  </div>
 
</div>



<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
    $("input[name='poption1']:radio,input[name='poption2']:radio").change(function () {
        var poid1 = $('input:radio[name="poption1"]:checked').val();
        var poid2 = $('input:radio[name="poption2"]:checked').val();

        var data = {
            poid1 : poid1,
            poid2 : poid2
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'option_change.php' ,
                data  : data ,
                dataType : 'json' ,
                error : function() {} ,
                success : function(data) {
                    console.log(data);
                    var price=parseInt(data.option_price1)+parseInt(data.option_price2)+<?php echo $rs->price;?>;
                    $("#pimg").attr("src", data.image_url);
                    $("#price").text(number_format(price));
                    $("#cnt").text(number_format(data.cnt));
                }
        });
    });

    function number_format(num){
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g,',');
    }


    $("#poption").change(function(){
        var poid = $("#poption option:selected").val();
       
        var data = {
            poid : poid
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'option_change.php' ,
                data  : data ,
                dataType : 'json' ,
                error : function() {} ,
                success : function(data) {
                    var price=parseInt(data.option_price)+<?php echo $rs->price;?>;
                    $("#pimg").attr("src", data.image_url);
                    $("#price").text(number_format(price));
                }
        });
    });
</script>
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>