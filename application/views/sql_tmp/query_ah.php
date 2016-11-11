<?php if(isset($chouchu)){?>
SELECT 
distinct A.hyno 
FROM  CALL010 A    
LEFT JOIN FMD060 D ON A.HYNO=D.HYNO    
LEFT JOIN FHD030 E ON A.HYNO=E.HYNO  
WHERE
<?php }else{
echo $query_select;
}?>
<?php 
echo chr('13'),$query_a,chr('13'),$query_b;
if($cwxz){
?>
AND A.HYNO NOT IN (SELECT HYNO FROM FMD180EXCEL1 WHERE CCBH in (<?php echo "'{$cwbh}".str_replace(',', "','{$cwbh}", $cwxz)."'";?>))
<?php }?>