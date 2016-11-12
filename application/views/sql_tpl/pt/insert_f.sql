insert into FMD180EXCEL1(ccbh,HYNO)
select '{$@_cwbh_@$}F' ,hyno 
from zkbak  where HYNO+REPLACE(rtrim(ltrim(REPLACE(tel,'-',''))),' ','') 
in ((select [会員№]+REPLACE(rtrim(ltrim([電話番号（ハイフンなし）])),' ','')    
from [不備電話$]));