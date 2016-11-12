select B.SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY B.SALONNO ORDER BY B.SALONNO, A.HYNO))) AS SEQ, A.HYNO,A.ztqf,A.phase,A.ccbh
from FHD030 A
LEFT JOIN CALL010 B ON A.HYNO=B.HYNO
where A.HYNO in (SELECT DISTINCT HYNO FROM FMD180EXCEL1 WHERE CCBH='{$@_cwbh_@$}E')