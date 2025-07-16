<?php
if ($form['datatable']) {
    $datatable = explode(',', $form['datatable']);
    $table = $datatable[0];
    $field = $datatable[1];
    echo CRUDBooster::first($table, ['id' => $value])->$field;
}
if ($form['dataquery']) {
    $dataquery = $form['dataquery'];
    $query = DB::select(DB::raw($dataquery));
    if ($query) {
        foreach ($query as $q) {
            if ($q->value == $value) {
                echo $q->label;
                break;
            }
        }
    }
}
if ($form['dataenum']) {
    echo $value;
}
?><?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/default/type_components/select/component_detail.blade.php ENDPATH**/ ?>