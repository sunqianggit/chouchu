insert into FMD180EXCEL1(hyno,ccbh)
SELECT HYNO,'{$@_cwbh_@$}G' FROM(
SELECT (SELECT COUNT(1) FROM [dbo].[f_split](TEL,'-')) AS ID,* 
FROM zkbak ) TT WHERE TT.ID<>3 ;