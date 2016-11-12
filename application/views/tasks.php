<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Welcome to CodeIgniter</title>
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/codemirror.css" rel="stylesheet">
        <!--<link href="<?php // echo base_url();    ?>assets/css/sqlcolors.css" rel="stylesheet">-->
        <script src="/assets/js/jquery-3.1.0.min.js"></script>
        <script src="/assets/js/codemirror.js"></script>
<!--        <script src="/assets/js/parsesql.js"></script>-->
    </head>
    <style>
        #body{
            margin-top: 20px;
            min-height: 1200px;
        }
        #main_layout{
            height: 100%;
        }
        #right_layout{
            border-left: 1px solid #e1e1e8;
            min-height: 1200px;
            height: 100%;
        }
        .sql_view{
            background-color: #f7f7f9;
            min-height: 550px;
        }
        .main_sql_edit{}
        H5{
            background-color: #e1e1e8;
            width: 100%;
            min-height: 35px;
            line-height: 35px;
            padding-left: 8px;
        }
    </style>
    <body>
        <div id="body" class="container">
            <div id="main_layout" class="col-xs-8">
                <form method="post" >
                    <div id="sql-view" class="sql_view row">
                        <textarea id="codeSelect" name="query[select]" ><?php echo $query_select;?></textarea>
                        <H5><b>查询范围</b></H5>
                        <textarea id="codeA" name="query[a]" ><?php echo $query_a;?></textarea>
                        <H5><b>除外条件</b></H5>
                        <textarea id="codeB" name="query[b]" ><?php echo $query_b;?></textarea>
                        <H5><b>AB结果存储至一个临时表中</b></H5>
                        <textarea id="deleteAB" name="delete[ab_bak]" ><?php echo $delete_ab_bak;?></textarea>
                        <textarea id="codeAB" name="back[ab]" ><?php echo $back_ab;?></textarea>
                        <H5><b>D: ①一度コールしたコール結果が「ＮＧ」の会員（「OK」、「不在」、「空白（NULL）」は抽出対象）</b></H5>
                        <textarea id="insertD" name="insert[d]" ><?php echo $insert_d;?></textarea>
                        <textarea id="codeD" name="query[d]" ><?php echo $query_d;?></textarea>
                        <H5><b>※　コール結果ＮＧ　状態区分：アプローチＯＫ，今回終了は抽出対象です  有这个,E不可执行.<BR/>
                                E:コール結果「ＮＧ」、コール状態（「OK」、「不在」）除外</b></H5>
                        <textarea id="insertE" name="insert[e]" ><?php echo $insert_e;?></textarea>
                        <textarea id="codeE" name="query[e]" ><?php echo $query_e;?></textarea>
                        <H5><b>F:不備電話</b></H5>
                        <textarea id="insertF" name="insert[f]" ><?php echo $insert_f;?></textarea>
                        <textarea id="codeF" name="query[f]" ><?php echo $query_f;?></textarea>
                        <H5><b>Ｇ電話番号上にハイフンが２個以外</b></H5>
                        <textarea id="insertG" name="insert[g]" ><?php echo $insert_g;?></textarea>
                        <textarea id="codeG" name="query[g]" ><?php echo $query_g;?></textarea>
                        <H5><b>H 同一電番（名寄せ）の方</b></H5>
                        <textarea id="insertH" name="insert[h]" ><?php echo $insert_h;?></textarea>
                        <textarea id="codeH" name="query[h]" ><?php echo $query_h;?></textarea>

                    </div>
                    <!--                <div class="main_sql_edit row">
                                        <textarea id="main-sql" rows="10" class="form-control"></textarea>
                                    </div>-->
                    <fieldset>
                        <legend>health information</legend>
                        <div class="form-group">
                            <label>抽出名</label>
                            <input name="cc_name" class="form-control" placeholder="抽出名称" value="<?php echo $cc_name;?>"/>
                            <label>抽出编号</label>
                            <input name="ccbh" class="form-control" placeholder="抽出编号" value="<?php echo $ccbh;?>"/>
                            <label for="exampleInputEmail1">除外对象(D,E,F,G,H)编号</label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" <?php echo set_my_checkbox('D', $cwxz[0])?> value="D"> D
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" <?php echo set_my_checkbox('E', $cwxz[1])?> value="E"> E
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" <?php echo set_my_checkbox('F', $cwxz[2])?> value="F"> F
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" <?php echo set_my_checkbox('G', $cwxz[3])?> value="G"> G
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" <?php echo set_my_checkbox('H', $cwxz[4])?>  value="H"> H
                            </label>
                            <input name="cwbh" class="form-control" placeholder="除外对象(D,E,F,G,H)编号" value="<?php echo $cwbh; ?>"/>
                            
                            <label for="exampleInputEmail1"><input type="checkbox" id="block_ctl" value="1">BLOCK别：</label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('1', $blocks[0])?> value="1"> 北海道
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('2', $blocks[1])?> value="2"> 東北
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('3', $blocks[2])?> value="3"> 東京
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('4', $blocks[3])?> value="4"> 中部
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('5', $blocks[4])?> value="5"> 北陸
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('6', $blocks[5])?> value="6"> 関西
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('7', $blocks[6])?> value="7"> 広島
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('8', $blocks[7])?> value="8"> 四国
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" <?php echo set_my_checkbox('9', $blocks[8])?> value="9"> 九州
                            </label>
                            <label for="exampleInputEmail1"><input type="checkbox" name="run_block_validate" <?php echo set_my_checkbox('1', $run_block_validate)?> value="1">是否执行BLOCK别SQL验证</label>
                        </div>
                        <div class="row">
                            <input class="btn btn-primary" name="<?php echo empty($ccbh) ? 'save' : 'update'?>" type="submit" value="生成SQL"/>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div id="right_layout" class="col-xs-4">
                <div class="row">
                    <textarea type="text" id="key" rows="6" name="key" class="form-control" placeholder="关键字检索"></textarea>
                </div>
                <div id="search-result-box" class="row">

                </div>
            </div>
        </div>
        <script>
            $('#block_ctl').click(function(){
                if($(this).is(':checked')){
                    $('.block_xx').attr("checked",true);
                }else{
                    $('.block_xx').attr("checked",false);
                }
            });
            $('#key').bind('input propertychange', function () {
                var key = $(this).val();
                $.post('/index.php/ajax/home/search', {'key': key}, function (response) {
                    if (response.status == 'success') {
                        $('#search-result-box').html(response.html);
                        $('.condition').unbind('click');
                    }
                }, 'json');
            });
//            $('#sql-view').dbclick(function(){
//                var org = $(this).html();
//                
//            });
        </script>
        <script type="text/javascript">
            var codeSelect = CodeMirror.fromTextArea('codeSelect', {
                height: "245px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorA = CodeMirror.fromTextArea('codeA', {
                height: "90px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorB = CodeMirror.fromTextArea('codeB', {
                height: "450px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var deleteAB = CodeMirror.fromTextArea('deleteAB', {
                height: "20px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorAB = CodeMirror.fromTextArea('codeAB', {
                height: "170px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var insertD = CodeMirror.fromTextArea('insertD', {
                height: "110px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorD = CodeMirror.fromTextArea('codeD', {
                height: "120px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var insertE = CodeMirror.fromTextArea('insertE', {
                height: "110px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorE = CodeMirror.fromTextArea('codeE', {
                height: "120px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var insertF = CodeMirror.fromTextArea('insertF', {
                height: "110px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorF = CodeMirror.fromTextArea('codeF', {
                height: "120px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var insertG = CodeMirror.fromTextArea('insertG', {
                height: "90px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorG = CodeMirror.fromTextArea('codeG', {
                height: "120px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var insertH = CodeMirror.fromTextArea('insertH', {
                height: "255px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorH = CodeMirror.fromTextArea('codeH', {
                height: "120px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            
        </script>
        <script src="/assets/js/bootstrap.min.js"></script>
    </body>
</html>