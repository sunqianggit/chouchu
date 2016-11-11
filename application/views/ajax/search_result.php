<?php foreach ($result as $key => $value) { ?>
<dl data-id="<?php echo $value['c_id']; ?>" class="condition">
        <dt class="list-group-item active">
                <?php echo $value['c_name']; ?>
        </dt>
        <dd class="list-group-item">
            <a href="javascript:;" class="glyphicon glyphicon-chevron-left">
            <?php echo $value['c_sql']; ?>
            </a>
        </dd>
        <dl>
            <?php
        }
        ?>