<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/favicon.ico">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
    <title>DataTables example - Row details</title>
    <!--<link href="/assets/css/bootstrap.min.css" rel="stylesheet">-->
    <!--<script src="/assets/js/jquery-3.1.0.min.js"></script>-->
    <script type="text/javascript" language="javascript" src="/assets/DataTables-1.10.12/media/js/jquery-1.12.3.min.js"></script>
</head>
<body class="dt-example">
    <div class="container">
        <section>
            <?php $this->load->controller('m_pt/m_tasks_dt/index');?>
        </section>
    </div>
</body>
</html>
