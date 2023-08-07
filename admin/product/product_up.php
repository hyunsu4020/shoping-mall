<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
if(!$_SESSION['AUID']){
    echo "<script>alert('권한이 없습니다.');history.back();</script>";
    exit;
}


$query="select * from category where step=1";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
    $cate1[]=$rs;
}

?>
<style>
    .thst{
        text-align: center;
    vertical-align: middle;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>


        <div style="text-align:center;padding:20px;"><H3>제품등록하기</H3></div>

<form method="post" action="pupok.php" onsubmit="return save()" enctype="multipart/form-data">        
        <table class="table table-sm table-bordered">
          <tbody>
            <input type="hidden" name="file_table_id" id="file_table_id" value="">
            <input type="hidden" name="contents" id="contents" value="">
           
          <tr>
            <th scope="row" class="thst">카테고리선택</th>
            <td>
            <div class="row g-3">
                <div class="col-md-4">
                    <select class="form-select" name="cate1" id="cate1" aria-label="Default select example">
                        <option value="">대분류</option>
                        <?php
                            foreach($cate1 as $c){
                        ?>
                            <option value="<?php echo $c->code;?>"><?php echo $c->name;?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="cate2" id="cate2" aria-label="Default select example">
                        <option value="">중분류</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="cate3" id="cate3" aria-label="Default select example">
                        <option value="">소분류</option>
                    </select>
                </div>
            </div>
            </td>
           
          </tr>
          <tr>
            <th scope="row" class="thst">제품명</th>
            <td><input type="text" class="form-control" name="name" id="name"></td>
          </tr>
          <tr>
            <th scope="row" class="thst">택배비</th>
            <td><input type="number" style="width:200px;text-align:right;" class="form-control" name="delivery_fee" id="delivery_fee"></td>
          </tr>
          <tr>
            <th scope="row" class="thst">제품가격</th>
            <td><input type="number" style="width:200px;text-align:right;" class="form-control" name="price" id="price"></td>
          </tr>
          <tr>
            <th scope="row" class="thst">세일가격</th>
            <td><input type="number" style="width:200px;text-align:right;" class="form-control" name="sale_price" id="sale_price"></td>
          </tr>
          <tr>
            <th scope="row" class="thst">세일비율</th>
            <td><input type="number" style="width:200px;text-align:right;" class="form-control" name="sale_ratio" id="sale_ratio"></td>
          </tr>
          <tr>
            <th scope="row" class="thst">전시옵션</th>
            <td>
            <input class="form-check-input" type="checkbox" name="ismain" id="ismain" value="1">메인&nbsp;
            <input class="form-check-input" type="checkbox" name="isnew" id="isnew" value="1">신제품&nbsp;

            <input class="form-check-input" type="checkbox" name="isbest" id="isbest" value="1">베스트&nbsp;

            <input class="form-check-input" type="checkbox" name="isrecom" id="isrecom" value="1">추천&nbsp;
            </td>
          </tr>
          <tr>
            <th scope="row" class="thst">위치지정</th>
            <td>
                <select class="form-select" name="locate" id="locate" aria-label="Default select example">
                    <option value="0">지정안함</option>
                    <option value="1">1번위치</option>
                    <option value="2">2번위치</option>
                </select>
            </td>
          </tr>
          <tr>
            <th scope="row" class="thst">판매종료일</th>
            <td>
                <input type="text" class="form-control" style="width: 272px;" name="sale_end_date" id="sale_end_date" value="<?php echo date("Y-m-d",strtotime("+6 month"))?>">
            </td>
          </tr>
          <tr>
            <th scope="row" class="thst">제품상세설명</th>
            <td>
                <div id="summernote"></div>
            </td>
          </tr>
          <tr>
            <th scope="row" class="thst">썸네일</th>
            <td><input type="file" class="form-control" name="thumbnail" id="thumbnail"></td>
          </tr>
          <tr style="max-height:100px;">
            <th scope="row" class="thst">추가이미지</th>
            <td style="height:100px;">
                <input type="file" multiple name="upfile[]" id="upfile" style="display:none;">
                <div id="target_file_wrap">
                    <a href="#" onclick="jQuery('#upfile').click()" class="btn btn-primary">이미지선택</a>
                </div>                    
                <div class="row row-cols-1 row-cols-md-6 g-4" id="imageArea">
                </div>
            </td>
          </tr>
          <tr>
            <th scope="row" class="thst">
                <select class="form-select" name="optionCate1" id="optionCate1">
                    <option value="컬러" selected>컬러</option>
                </select>
            </th>
            <td>
                <table class="table">
                <thead>
                    <tr>
                    <th scope="col">옵션명</th>
                    <th scope="col">가격</th>
                    <th scope="col">이미지</th>
                    </tr>
                </thead>
                <tbody id="option1">
                    <tr id="optionTr1">
                    <th scope="row">
                        <input class="form-control opname1" type="text" style="max-width:200px;" value="" name="optionName1[]">
                    </th>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control" style="max-width:100px;" value="0" name="optionPrice1[]">
                            <span class="input-group-text">원</span>
                        </div>
                    </td>
                    <td>
                        <input type="file" class="form-control" name="optionImage1[]" id="optionImage1">
                    </td>
                    </tr>
                </tbody>
                </table>
                <button class="btn btn-secondary" type="button" onclick="opt1cp()">옵션추가</button>
            </td>
          </tr>

          <tr>
            <th scope="row" class="thst">
                <select class="form-select" name="optionCate2" id="optionCate2">
                    <option value="사이즈" selected>사이즈</option>
                </select>
            </th>
            <td>
                <table class="table">
                <thead>
                    <tr>
                    <th scope="col" style="width:300px;">옵션명</th>
                    <th scope="col">가격</th>
                    </tr>
                </thead>
                <tbody id="option2">
                    <tr id="optionTr2">
                    <th scope="row">
                        <input class="form-control opname2" type="text" style="max-width:200px;" value="" name="optionName2[]">
                    </th>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control" style="max-width:100px;" value="0" name="optionPrice2[]">
                            <span class="input-group-text">원</span>
                        </div>
                    </td>
                    </tr>
                </tbody>
                </table>
                <button class="btn btn-secondary" type="button" onclick="opt2cp()">옵션추가</button>
            </td>
          </tr>
          <tr>
            <th scope="row" class="thst">재고</th>
            <td>
                <div id="wmsArea">
                </div>
                <button class="btn btn-secondary" type="button" onclick="wmsIns()">재고입력</button>
            </td>
          </tr>
          </tbody>
        </table>
       
        <button class="btn btn-primary" type="submit">등록완료</button>
</form>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>

    function wmsIns(){
       
        $("#wmsArea").html("");
        var addHtml="";
        $(".opname1").each(function(idx){  
            var n1 = $(this).val();
            $(".opname2").each(function(idx){
                var n2 = $(this).val();
                if(n1 && n2){
                    addHtml+='<li style="display:flex;padding-bottom:5px;">'+n1+' X '+n2+'&nbsp;<input type="text" class="form-control" style="max-width:100px;" name="wms[]" value="0">개</li>';
                }else if(n1 && !n2){
                    addHtml+='<li style="display:flex;padding-bottom:5px;">'+n1+'&nbsp;<input type="text" class="form-control" style="max-width:100px;" name="wms[]" value="0">개</li>';
                }else if(!n1 && n2){
                    addHtml+='<li style="display:flex;padding-bottom:5px;">'+n2+'&nbsp;<input type="text" class="form-control" style="max-width:100px;" name="wms[]" value="0">개</li>';
                }else{
                    addHtml+='<li style="display:flex;padding-bottom:5px;">&nbsp;<input type="text" class="form-control" style="max-width:100px;" name="wms[]" value="0">개</li>';
                }
            });        
        });

        $("#wmsArea").append(addHtml);
    }

    function opt1cp(){
        var addHtml=$("#optionTr1").html();
        var addHtml="<tr>"+addHtml+"</tr>";
        $("#option1").append(addHtml);
    }

    function opt2cp(){
        var addHtml=$("#optionTr2").html();
        var addHtml="<tr>"+addHtml+"</tr>";
        $("#option2").append(addHtml);
    }

    function save(){
        var markup = $('#summernote').summernote('code');
          var contents=encodeURIComponent(markup);
          $("#contents").val(contents);
    }

    $(function(){
        $('#summernote').summernote({
            height: 300
        });
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

    $("#upfile").change(function(){

        var files = $('#upfile').prop('files');
        for(var i=0; i < files.length; i++) {
            attachFile(files[i]);
        }

        $('#upfile').val('');

    });  

    function attachFile(file) {
    var formData = new FormData();
    formData.append("savefile", file);
    $.ajax({
        url: 'product_save_image.php',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType : 'json' ,
        type: 'POST',
        success: function (return_data) {
            if(return_data.result=="member"){
                alert('로그인 하십시오.');
                return;
            }else if(return_data.result=="size"){
                alert('10메가 이하만 첨부할 수 있습니다.');
                return;
            }else if(return_data.result=="image"){
                alert('이미지 파일만 첨부할 수 있습니다.');
                return;
            }else if(return_data.result=="error"){
                alert('첨부하지 못했습니다. 관리자에게 문의하십시오.');
                return;
            }else{
                imgid = $("#file_table_id").val() + return_data.imgid + ",";
                $("#file_table_id").val(imgid);
                var html = "<div class='col' id='f_"+return_data.imgid+"'><div class='card h-100'><img src='/pdata/"+return_data.savename+"' class='card-img-top'><div class='card-body'><button type='button' class='btn btn-warning' onclick='file_del("+return_data.imgid+")'>삭제</button></div></div></div>";
                $("#imageArea").append(html);
            }
        }
    });

    }

    function file_del(imgid){

        if(!confirm('삭제하시겠습니까?')){
        return false;
        }
           
        var data = {
            imgid : imgid
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'image_delete.php' ,
                data  : data ,
                dataType : 'json' ,
                error : function() {} ,
                success : function(return_data) {
                    if(return_data.result=="member"){
                        alert('로그인 하십시오.');
                        return;
                    }else if(return_data.result=="my"){
                        alert('본인이 작성한 제품의 이미지만 삭제할 수 있습니다.');
                        return;
                    }else if(return_data.result=="no"){
                        alert('삭제하지 못했습니다. 관리자에게 문의하십시오.');
                        return;
                    }else{
                        $("#f_"+imgid).hide();
                    }
                }
        });

        }

</script>    
<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>