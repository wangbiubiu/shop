<?php
$this->registerCssFile('@web/datatable/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/datatable/media/js/jquery.js');
$this->registerJsFile('@web/datatable/media/js/jquery.dataTables.js');
?>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>Column 1</th>
        <th>Column 2</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Row 1 Data 1</td>
        <td>Row 1 Data 2</td>
    </tr>
    <tr>
        <td>Row 2 Data 1</td>
        <td>Row 2 Data 2</td>
    </tr>
    </tbody>
</table>

<?php
$this->registerJs(new \yii\web\JsExpression(
                      <<<JS
$(document).ready( function () {
    $('#table_id_example').DataTable({
        scrollY: 300,
        paging: false
    });
} );
JS
                  ));
?>