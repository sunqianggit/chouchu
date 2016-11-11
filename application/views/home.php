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
                        <textarea id="codeSelect" name="query[select]" >
SELECT distinct 
   CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY A.SALONNO ORDER BY A.SALONNO))) AS SEQ, 
     A.SALONNO,A.HYNO,A.HYZB,A.HYM,A.HYMKN ,A.CHGRSLNO,ISNULL(C2.SLNMC,'')CHSLNMC,ISNULL(B.MC,'') ZS,
    A.SNYR,ISNULL(D.ZJGRJE,'') ZJGRJE,ISNULL(D.ZJGRR,'') ZJGRR,isnull(c.SLNDELDT,''),A.TEL,
    a.QHYNO,A.QHYM,ISNULL(E.DLDLLQF,'') DLDLLQF ,
    ISNULL(E.ZTQF,'') ZTQF ,ISNULL(E.PHASE,'') PHASE,E.CCBH,E.QHCALLRS,
 A.BLOCKNO,F.MC
FROM  CALL010 A 
LEFT JOIN FMD010 A0 ON A.HYNO =A0.HYNO 
LEFT JOIN FMD080 B ON  A.DDFXBH=B.ID
LEFT JOIN FMD070 C ON A.SALONNO=C.SLNCD
LEFT JOIN FMD070 C2 ON A.CHGRSLNO =C2.SLNCD
LEFT JOIN FMD060 D ON A.HYNO=D.HYNO 
LEFT JOIN FHD030 E ON A.HYNO=E.HYNO 
LEFT JOIN FMD110 F ON A.BLOCKNO = F.MCKEY AND F.GLBH = '004' 
WHERE </textarea>
                        <H5><b>查询范围</b></H5>
                        <textarea id="codeA" name="query[a]" >
--抽出コード4639～4647の最新コール結果が「OK」と「不在」の方を抽出してしてください
A.HYNO in 
(select  a.HYNO   from FHD010 a  
left join fhd030 b on a.hyno=b.hyno 
where a.ccbh between '4639' and '4647' and b.PHASE in ('11','13')) 
                        </textarea>
                        <H5><b>除外条件</b></H5>
                        <textarea id="codeB" name="query[b]" >
--②「除外」を除く 
AND ISNULL(A.HYNO,'') not in (select distinct HYNO from FMD200 )  
--④元支店長、元サロン長、Ｂ会員、友の会会員(会員№4000000からの方）
AND A.HYNO NOT IN ( SELECT DISTINCT SLNKAINO  FROM FMD070 WHERE  ISNULL(SLNDELDT,'')<>'00000000') 
AND A.HYZB<>'B' AND SUBSTRING (A.HYNO,1,1) <>'4'    
--⑤2016年4月～現在まで購入のある方（直近購入） 
AND isnull(D.zjgrr,'') < '20160401'  
--⑥80歳以上の方（1936年12月31日までに生まれた人） 
AND ISNULL(a.SNYR,'')>'19361231'  
--⑦【岩手県青森県コール保留地域】
AND ISNULL(A.HYNO,'') NOT IN (SELECT HYNO FROM FMD180EXCEL1 WHERE CCBH='H49H')   
--⑪【東京】サロン№8631サロン光来の会員で地位ランク愛用者の方 
and ISNULL(a.HYNO,'') not in (select HYNO from CALL010 where SALONNO='008631' and XZDW = '10')
--⑩【中部】サロン№９３０２サロン沖田の会員の方（全員） 
--⑨【北陸】サロン№5271サロンドムス所属の方 
--⑬【北陸】サロン№516サロン金沢所属の方 
--⑭【東北】サロン№6538サロン月花美人・サロン№7432サロン珊瑚美月・サロン№9272サロン夢花月　所属の方 
and a.salonno not in ('005271','009302','000516','006538','007432','009272') 
--⑧【北海道】会員№で依頼した方（除外対象追加　会員№553968髙橋敬子　除外してください）
--⑫【東京】会員№8821（旧サロンゆめゆめ）の傘下の会員  
and ISNULL(a.HYNO,'') not in (select distinct hyno from FMD180EXCEL2 where ccbh in ('0059','0054'))  
and ISNULL(a.HYNO,'')<>'0553968'
--⑰会員の名前で名前の前に☆がついている会員 
AND SUBSTRING ( rtrim(ltrim(A.HYM)),1,1)<>'☆'  
--⑯868班､963班～996班、1037班～1070班の方
AND ISNULL(a.HYNO,'') not IN (select HYNO from FHD010  where  BBH in ('0868') 
OR  BBH between '963' and '996' or BBH between '1037' and '1070' ) 
--⑯熊本県、大分県由布市在住の方 
and a.HYNO not in ( select HYNO from ZK_DONOTDELETE where qf in('001','008'))
                        </textarea>
                        <H5><b>AB结果存储至一个临时表中</b></H5>
                        <textarea id="deleteAB" name="delete[ab_bak]" >
DELETE ZKBAK ;
                        </textarea>
                        <textarea id="codeAB" name="back[ab]" >
INSERT INTO ZKBAK(BBH,HYNO,TEL,CCBH)
SELECT    distinct 'zk',a.HYNO AS HYNO,A.TEL AS TEL,a.blockno
FROM CALL010 a 
LEFT JOIN FMD060 D ON A.HYNO=D.HYNO 
LEFT JOIN FMD070 c ON A.SALONNO =c.SLNCD 
left join FHD030 E on a.HYNO=E.HYNO 
LEFT JOIN FMD010 A0 ON A.HYNO =A0.HYNO 
WHERE 
--查询范围
--除外条件
                        </textarea>
                        <H5><b>D: ①一度コールしたコール結果が「ＮＧ」の会員（「OK」、「不在」、「空白（NULL）」は抽出対象）</b></H5>
                        <textarea id="insertD" name="insert[d]" >
insert into FMD180EXCEL1(ccbh,HYNO)
SELECT '{$@_cwbh_@$}D',A.HYNO
from zkbak A 
left join FHD030 B on A.HYNO =B.HYNO 
where ISNULL(b.ztqf,'') not in ('','1','2') ;
                        </textarea>
                        <textarea id="codeD" name="query[d]" >
select B.SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY B.SALONNO ORDER BY B.SALONNO, A.HYNO))) AS SEQ, A.HYNO,A.ztqf,A.phase,A.ccbh
from FHD030 A
LEFT JOIN CALL010 B ON A.HYNO=B.HYNO
where A.HYNO in (SELECT DISTINCT HYNO FROM FMD180EXCEL1 WHERE CCBH='{$@_cwbh_@$}D')
                        </textarea>
                        <H5><b>※　コール結果ＮＧ　状態区分：アプローチＯＫ，今回終了は抽出対象です  有这个,E不可执行.<BR/>
                                E:コール結果「ＮＧ」、コール状態（「OK」、「不在」）除外</b></H5>
                        <textarea id="insertE" name="insert[e]" >
insert into FMD180EXCEL1(ccbh,HYNO)
SELECT '{$@_cwbh_@$}E',A.HYNO
from zkbak A 
left join FHD030 B on A.HYNO =B.HYNO 
where isnull(b.ztqf,'')+ ISNULL (b.phase,'') in ('112','212')
                        </textarea>
                        <textarea id="codeE" name="query[e]" >
select B.SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY B.SALONNO ORDER BY B.SALONNO, A.HYNO))) AS SEQ, A.HYNO,A.ztqf,A.phase,A.ccbh
from FHD030 A
LEFT JOIN CALL010 B ON A.HYNO=B.HYNO
where A.HYNO in (SELECT DISTINCT HYNO FROM FMD180EXCEL1 WHERE CCBH='{$@_cwbh_@$}E')
                        </textarea>
                        <H5><b>F:不備電話</b></H5>
                        <textarea id="insertF" name="insert[f]" >
insert into FMD180EXCEL1(ccbh,HYNO)
select '{$@_cwbh_@$}F' ,hyno 
from zkbak  where HYNO+REPLACE(rtrim(ltrim(REPLACE(tel,'-',''))),' ','') 
in ((select [会員№]+REPLACE(rtrim(ltrim([電話番号（ハイフンなし）])),' ','')    
from [不備電話$]));
                        </textarea>
                        <textarea id="codeF" name="query[f]" >
select SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY A.SALONNO ORDER BY A.SALONNO, A.HYNO))) AS SEQ,hyno ,TEL
from call010 A
where HYNO in (SELECT HYNO FROM FMD180EXCEL1 WHERE CCBH='{$@_cwbh_@$}F')
ORDER BY SALONNO,HYNO 
                        </textarea>
                        <H5><b>Ｇ電話番号上にハイフンが２個以外</b></H5>
                        <textarea id="insertG" name="insert[g]" >
insert into FMD180EXCEL1(hyno,ccbh)
SELECT HYNO,'{$@_cwbh_@$}G' FROM(
SELECT (SELECT COUNT(1) FROM [dbo].[f_split](TEL,'-')) AS ID,* 
FROM zkbak ) TT WHERE TT.ID<>3 ;
                        </textarea>
                        <textarea id="codeG" name="query[g]" >
select A.SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY A.SALONNO ORDER BY A.SALONNO, A.HYNO))) AS SEQ, A.hyno ,A.TEL
from call010 A
where A.HYNO in (SELECT HYNO FROM FMD180EXCEL1 WHERE CCBH='{$@_cwbh_@$}G')
ORDER BY A.SALONNO,A.HYNO
                        </textarea>
                        <H5><b>H 同一電番（名寄せ）の方</b></H5>
                        <textarea id="insertH" name="insert[h]" >
insert into FMD180EXCEL1(hyno,ccbh)
SELECT  TT.HYNO,'V25H' FROM 
(SELECT C.HYNO,CASE WHEN ISNULL(D.TEL,'') = '' THEN C.TEL ELSE D.TEL END AS TEL FROM 
(SELECT DIS.* FROM 
(SELECT  V1.HYNO AS HYNO,V1.TEL AS TEL FROM 
(select HYNO,TEL from zkbak) V1 ) DIS ) C
LEFT JOIN(SELECT HYNO,TEL,HYM FROM FHD030) D ON C.HYNO = D.HYNO ) TT
WHERE REPLACE(TT.TEL,'-','') IN (SELECT REPLACE(AA.TEL, '-', '')
FROM (SELECT CASE WHEN ISNULL(B.TEL, '') = '' THEN A.TEL ELSE B.TEL END AS TEL
FROM (SELECT DISTINCT  HYNO, TEL FROM 
(select HYNO,TEL from zkbak) TM ) A
LEFT JOIN (SELECT HYNO,TEL FROM FHD030) B ON A.HYNO = B.HYNO ) AA
WHERE ISNULL(AA.TEL, '') <> ''
GROUP BY REPLACE(AA.TEL, '-', '')
HAVING COUNT(AA.TEL) > 1)  
                        </textarea>
                        <textarea id="codeH" name="query[h]" >
select A.SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY A.SALONNO ORDER BY A.SALONNO, A.HYNO))) AS SEQ, A.hyno ,A.TEL
from call010 A
where A.HYNO in (SELECT HYNO FROM FMD180EXCEL1 WHERE CCBH='V25H')
ORDER BY A.SALONNO,A.HYNO
                        </textarea>

                    </div>
                    <!--                <div class="main_sql_edit row">
                                        <textarea id="main-sql" rows="10" class="form-control"></textarea>
                                    </div>-->
                    <fieldset>
                        <legend>health information</legend>
                        <div class="form-group">
                            <label>抽出名</label>
                            <input name="cc_name" class="form-control" placeholder="抽出名称" value=""/>
                            <label>抽出编号</label>
                            <input name="ccbh" class="form-control" placeholder="抽出编号" value=""/>
                            <label for="exampleInputEmail1">除外对象(D,E,F,G,H)编号</label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" checked="checked" value="D"> D
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" checked="checked" value="E"> E
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" checked="checked" value="F"> F
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" checked="checked" value="G"> G
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cwxz[]" checked="checked" value="H"> H
                            </label>
                            <input name="cwbh" class="form-control" placeholder="除外对象(D,E,F,G,H)编号" value="V99"/>
                            
                            <label for="exampleInputEmail1"><input type="checkbox" id="block_ctl" value="1">BLOCK别：</label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="1"> 北海道
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="2"> 東北
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="3"> 東京
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="4"> 中部
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="5"> 北陸
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="6"> 関西
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="7"> 広島
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="8"> 四国
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" class="block_xx" name="block[]" value="9"> 九州
                            </label>
                            <label for="exampleInputEmail1"><input type="checkbox" name="run_block_validate" value="1">是否执行BLOCK别SQL验证</label>
                        </div>
                        <div class="row">
                            <input class="btn btn-primary" name="save" type="submit" value="保存"/>
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