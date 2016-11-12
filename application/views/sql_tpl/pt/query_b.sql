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