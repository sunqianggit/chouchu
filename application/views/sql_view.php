<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Welcome to CodeIgniter</title>
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/codemirror.css" rel="stylesheet">
        <!--<link href="<?php // echo base_url();   ?>assets/css/sqlcolors.css" rel="stylesheet">-->
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
                        <H5><b>范围查询SQL</b></H5>
                        <textarea id="sql_a" name="query[A]" ><?php echo $query_select, $query_a?></textarea>
                        <H5><b>除外条件查询SQL</b></H5>
                        <textarea id="sql_ab" name="query[B]" ><?php echo $query_select, $query_a, $query_b?></textarea>
                        <H5><b>AB结果存储至一个临时表中</b></H5>
                        <textarea id="delete_ab_bak" name="query[DEL_AB_BAK]" ><?php echo $delete_ab_bak; ?></textarea>
                        <textarea id="sql_ab_bak" name="query[AB_BAK]" ><?php echo $back_ab, $query_a, $query_b?></textarea>
                        <H5><b>D: ①一度コールしたコール結果が「ＮＧ」の会員（「OK」、「不在」、「空白（NULL）」は抽出対象）</b></H5>
                        <textarea id="insertD" name="query[InsertD]" ><?php echo $insert_d;?></textarea>
                        <textarea id="sql_d" name="query[D]" ><?php echo $query_d;?></textarea>
                        <H5><b>※　コール結果ＮＧ　状態区分：アプローチＯＫ，今回終了は抽出対象です  有这个,E不可执行.<BR/>
                                E:コール結果「ＮＧ」、コール状態（「OK」、「不在」）除外</b></H5>
                        <textarea id="insertE" name="query[InsertE]" ><?php echo $insert_e;?></textarea>
                        <textarea id="sql_e" name="query[E]" ><?php echo $query_e;?></textarea>
                        <H5><b>F:不備電話</b></H5>
                        <textarea id="insertF" name="query[InsertF]" ><?php echo $insert_f;?></textarea>
                        <textarea id="sql_f" name="query[F]" ><?php echo $query_f;?></textarea>
                        <H5><b>Ｇ電話番号上にハイフンが２個以外</b></H5>
                        <textarea id="insertG" name="query[InsertG]" ><?php echo $insert_g;?></textarea>
                        <textarea id="sql_g" name="query[G]" ><?php echo $query_g;?></textarea>
                        <H5><b>H 同一電番（名寄せ）の方</b></H5>
                        <textarea id="insertH" name="query[InsertH]" ><?php echo $insert_h;?></textarea>
                        <textarea id="sql_h" name="query[H]" ><?php echo $query_h;?></textarea>
                        <H5><b>A~H 抽出SQL</b></H5>
                        <textarea id="sql_ah" name="query[AH]" ><?php echo $query_ah;?></textarea>
                        <H5><b>抽出登录SQL</b></H5>
                        <textarea id="dl_sql" name="query[DL]" ><?php echo $dl_sql;?></textarea>
                        <?php 
                        $block_arr = array();
                        for($i = 1; $i < 10; $i++){
                            $block_sql = '';
                            eval('if(isset($block_sql'.$i.')){ $block_sql = $block_sql'.$i.'; }');
                            if($block_sql){
                                $block_arr[] = $i;
                            ?>
                        <H5><b>BLOCK <?php echo $i;?></b></H5>
                        <textarea id="block_sql<?php echo $i;?>" name="query['BLOCK<?php echo $i;?>']" ><?php echo $block_sql;?></textarea>
                        <?php }
                        }?>
                    </div>
                    <div class="row">
                        <input class="btn btn-primary" name="save" type="submit" value="保存"/>
                    </div>
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
            CodeMirror.fromTextArea('sql_a', {
                height: "245px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            CodeMirror.fromTextArea('sql_ab', {
                height: "370px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            CodeMirror.fromTextArea('delete_ab_bak', {
                height: "20px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            CodeMirror.fromTextArea('sql_ab_bak', {
                height: "450px",
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
            var editorD = CodeMirror.fromTextArea('sql_d', {
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
            var editorE = CodeMirror.fromTextArea('sql_e', {
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
            var editorF = CodeMirror.fromTextArea('sql_f', {
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
            var editorG = CodeMirror.fromTextArea('sql_g', {
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
            var editorH = CodeMirror.fromTextArea('sql_h', {
                height: "120px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorAH = CodeMirror.fromTextArea('sql_ah', {
                height: "350px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            var editorDL = CodeMirror.fromTextArea('dl_sql', {
                height: "350px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            <?php foreach($block_arr as $val){?>
            CodeMirror.fromTextArea('block_sql<?php echo $val;?>', {
                height: "350px",
                width: "100%",
                parserfile: "parsesql.js",
                stylesheet: "/assets/css/sqlcolors.css",
                path: "/assets/js/",
                textWrapping: false
            });
            <?php }?>
        </script>
        <script src="/assets/js/bootstrap.min.js"></script>
    </body>
</html>