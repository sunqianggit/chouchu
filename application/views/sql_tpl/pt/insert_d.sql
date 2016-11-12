insert into FMD180EXCEL1(ccbh,HYNO)
SELECT '{$@_cwbh_@$}D',A.HYNO
from zkbak A 
left join FHD030 B on A.HYNO =B.HYNO 
where ISNULL(b.ztqf,'') not in ('','1','2') ;
