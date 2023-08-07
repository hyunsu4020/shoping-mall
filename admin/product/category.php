<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/header.php";
$query="select * from category where step=1";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($rs = $result->fetch_object()){
    $cate1[]=$rs;
}

?>
    <div style="margin-top:20px;">
        <form class="row g-3">
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
        </form>
    </div>
    <div>
        <br><br>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cate1Modal">
        대분류등록
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cate2Modal">
        중분류등록
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cate3Modal">
        소분류등록
        </button>

        <!-- Modal -->
        <div class="modal fade" id="cate1Modal" tabindex="-1" aria-labelledby="cate1ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cate1ModalLabel">대분류등록</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control" name="code1" id="code1" type="text" placeholder="코드명을 입력하세요">
                </div>

                <div class="col-md-6">
                    <input class="form-control" name="name1" id="name1" type="text" placeholder="대분류명을 입력하세요">
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="catetory_save(1)">등록</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="cate2Modal" tabindex="-1" aria-labelledby="cate2ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cate2ModalLabel">중분류등록</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                        $query="select * from category where step=1";
                        $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
                        while($rs = $result->fetch_object()){
                            $pcode2[]=$rs;
                        }
                    ?>
                    <select class="form-select" name="pcode" id="pcode2" aria-label="Default select example">
                        <option value="">대분류를 선택하세요.</option>
                        <?php
                            foreach($pcode2 as $p){
                        ?>
                            <option value="<?php echo $p->code;?>"><?php echo $p->name;?></option>
                        <?php }?>
                    </select>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control" name="code2" id="code2" type="text" placeholder="코드명을 입력하세요">
                    </div>

                    <div class="col-md-6">
                        <input class="form-control" name="name2" id="name2" type="text" placeholder="중분류명을 입력하세요">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="catetory_save(2)">등록</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="cate3Modal" tabindex="-1" aria-labelledby="cate3ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cate3ModalLabel">소분류등록</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                    <?php
                        $pcode2= array();
                        $query="select * from category where step=1";
                        $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
                        while($rs = $result->fetch_object()){
                            $pcode2[]=$rs;
                        }
                    ?>
                    <div class="col-md-6">
                        <select class="form-select" name="pcode" id="pcode2_1" aria-label="Default select example">
                            <option value="">대분류를 선택하세요.</option>
                            <?php
                                foreach($pcode2 as $p){
                            ?>
                                <option value="<?php echo $p->code;?>"><?php echo $p->name;?></option>
                            <?php }?>
                        </select>
                    </div>                        
                    <div class="col-md-6">
                        <select class="form-select" name="pcode3" id="pcode3" aria-label="Default select example">
                            <option value="">대분류를 먼저 선택하세요.</option>
                        </select>
                    </div>                        
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control" name="code3" id="code3" type="text" placeholder="코드명을 입력하세요">
                    </div>

                    <div class="col-md-6">
                        <input class="form-control" name="name3" id="name3" type="text" placeholder="소분류명을 입력하세요">
                    </div>
                </div>
                <div style="padding:10px;text-align:right;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                <button type="button" class="btn btn-primary" onclick="catetory_save(3)">등록</button>
                </div>
            </div>
            </div>
            <div class="modal-footer">
               
            </div>
            </div>
        </div>
        </div>

    </div>


<script>
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

    function catetory_save(step){
        var name = $("#name"+step).val();
        var code = $("#code"+step).val();
        var pcode = $("#pcode"+step+" option:selected").val();

        if(step>1 && !pcode){
            alert('부모 코드를 선택하세요.');
            return;
        }

        if(!code){
            alert('코드명을 입력하세요.');
            return;
        }

        if(!name){
            alert('분류명을 입력하세요.');
            return;
        }

       
       
        var data = {
            name : name,
            code : code,
            pcode : pcode,
            step : step
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'save_category.php' ,
                data  : data ,
                dataType : 'json' ,
                error : function() {} ,
                success : function(return_data) {
                    if(return_data.result==1){
                        alert('등록했습니다.');
                        location.reload();
                    }else if(return_data.result==-1){
                        alert('코드나 분류명이 이미 사용중입니다.');
                        location.reload();
                    }else{
                        alert('등록하지 못했습니다.');
                    }
                }
        });
    }

    $("#pcode2_1").change(function(){
        var cate = $("#pcode2_1 option:selected").val();
       
        var data = {
            cate : cate
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'category4.php' ,
                data  : data ,
                dataType : 'html' ,
                error : function() {} ,
                success : function(return_data) {
                    $("#pcode3").html(return_data);
                }
        });
    });

</script>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/footer.php";
?>