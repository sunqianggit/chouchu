<link rel="stylesheet" type="text/css" href="/assets/DataTables-1.10.12/media/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="/assets/DataTables-1.10.12/examples/resources/syntax/shCore.css">
<link rel="stylesheet" type="text/css" href="/assets/DataTables-1.10.12/examples/resources/demo.css">
<style type="text/css" class="init">

    td.details-control {
        background: url('/assets/DataTables-1.10.12/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.details td.details-control {
        background: url('/assets/DataTables-1.10.12/examples/resources/details_close.png') no-repeat center center;
    }
    tr.td-gray td{
        background-color:#D0D0D0;
    }
    tr.detail-thead th{
        background-color:navajowhite;
    }
    tr.td-red td{
        background-color: tomato;
    }
</style>
<script type="text/javascript" language="javascript" src="/assets/DataTables-1.10.12/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="/assets/DataTables-1.10.12/examples/resources/syntax/shCore.js"></script>
<script type="text/javascript" language="javascript" src="/assets/DataTables-1.10.12/examples/resources/demo.js"></script>
<script type="text/javascript" language="javascript" class="init">


    function format(d) {
        return d.detail_view;
    }

    $(document).ready(function () {
        var dt = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "/m_pt/m_tasks_dt/dataTable",
            "columns": [
                {"data": "$.task_chk", "orderable": false, "searchable": 'false'},
                {
                    "class": "details-control",
                    "orderable": false,
                    "data": null,
                    "defaultContent": "",
                    "orderable": false,
                            "searchable": 'false'
                },
                {"data": "cc_name"},
                {"data": "ccbh"},
                {"data": "cwxz"},
                {"data": "cwbh"},
                {"data": "status"},
                {"data": "create_time"},
                {"data": "$.action"},
            ],
            "order": [[1, 'asc']],
            "fnDrawCallback": function (oSettings) {
                $('.edit-btn').click(function(){
                    var id = $(this).data('id');
                    window.location.href='/tasks/pt/'+id;
                });
            }
        });

        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#example tbody').on('click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dt.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details');
                row.child(format(row.data())).show();
                row.child(format(row.data())).css({'border':'1px solid red;'});

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on('draw', function () {
            $.each(detailRows, function (i, id) {
                $('#' + id + ' td.details-control').trigger('click');
            });
        });
        
        $('#run-chouchu-btn').click(function(){
            strSel = '';
            $("[name='pt_task']:checked").each(function(index, element) {
                strSel += $(this).val() + ",";
            });
            $.post('/ajax/serv/exec_tasks', {'tasks':strSel}, function(data){
                alert(data.msg);
            },'json');
        });
    });

</script>
<h1>普通抽出 <span>Row details</span></h1>
<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>抽出名称</th>
            <th>抽出编号</th>
            <th>除外选择</th>
            <th>除外编号</th>
            <th>状态</th>
            <th>创建日期</th>
            <th>操作</th>
        </tr>
    </thead>
<!--                <tfoot>
        <tr>
            <th></th>
            <th>First name</th>
            <th>Last name</th>
        </tr>
    </tfoot>-->
</table>
<fieldset>
    <legend>操作</legend>
    <input id="run-chouchu-btn" type="button" value="执行抽出SQL">
</fieldset>