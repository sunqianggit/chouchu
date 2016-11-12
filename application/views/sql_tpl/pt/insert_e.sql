insert into FMD180EXCEL1(ccbh,HYNO)
SELECT '{$@_cwbh_@$}E',A.HYNO
from zkbak A 
left join FHD030 B on A.HYNO =B.HYNO 
where isnull(b.ztqf,'')+ ISNULL (b.phase,'') in ('112','212')