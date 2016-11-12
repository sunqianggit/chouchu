--抽出コード4639～4647の最新コール結果が「OK」と「不在」の方を抽出してしてください
A.HYNO in 
(select  a.HYNO   from FHD010 a  
left join fhd030 b on a.hyno=b.hyno 
where a.ccbh between '4639' and '4647' and b.PHASE in ('11','13'))