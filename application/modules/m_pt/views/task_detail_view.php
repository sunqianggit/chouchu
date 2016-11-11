<?php if(!empty($tasks)){
    $status_arr = array(
        '未执行',
        '已执行',
        '执行失败',
    );
    $status_class = array(
        0 => 'td-gray',
        1 => '',
        2 => 'td-red'
    );
    ?>
<table class="display" cellspacing="0" width="40%">
    <thead>
        <tr class="detail-thead">
            <th>SQL名称</th>
            <th>件数</th>
            <th>状态</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($tasks as $task){?>
        <tr class="<?php echo $status_class[$task['status']];?>">
            <td><?php echo $task['name'];?></td>
            <td><?php echo $task['num_rows'];?></td>
            <td><?php echo $status_arr[$task['status']];?></td>
        </tr>
        <?php }?>
    </tbody>
</table>
<?php }?>