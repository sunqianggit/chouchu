<?php
echo "抽出：{$num_rows}件".CSVFILE_LINE.
CSVFILE_LINE.
"Ａ：{$fw_note}".CSVFILE_LINE.
CSVFILE_LINE.
'Ｂ：除外：'.CSVFILE_LINE.
$cw_note.CSVFILE_LINE.
CSVFILE_LINE.
'Ｄ～Ｈ：'.CSVFILE_LINE.
'D:一度コールしたコール結果が「NG」の方　（「ＯＫ」「不在」「空白（NULL）」は抽出対象）'.CSVFILE_LINE.
'E:コール結果「ＮＧ」、コール状態（「OK」、「不在」）除外'.CSVFILE_LINE.
'F:不備電話'.CSVFILE_LINE.
'Ｇ電話番号上にハイフンが２個以外'.CSVFILE_LINE.
'H 同一電番（名寄せ）の方'.CSVFILE_LINE.
CSVFILE_LINE.
$task_sql;
?>