select A.SALONNO,
    CONVERT(INT,CONVERT(NVARCHAR,ROW_NUMBER()  OVER(PARTITION BY A.SALONNO ORDER BY A.SALONNO, A.HYNO))) AS SEQ, A.hyno ,A.TEL
from call010 A
where A.HYNO in (SELECT HYNO FROM FMD180EXCEL1 WHERE CCBH='{$@_cwbh_@$}H')
ORDER BY A.SALONNO,A.HYNO