select SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY A.SALONNO ORDER BY A.SALONNO, A.HYNO))) AS SEQ,hyno ,TEL
from call010 A
where HYNO in (SELECT HYNO FROM FMD180EXCEL1 WHERE CCBH='{$@_cwbh_@$}F')
ORDER BY SALONNO,HYNO 