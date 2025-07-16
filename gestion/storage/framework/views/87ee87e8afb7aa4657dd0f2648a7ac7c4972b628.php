<?php $__env->startSection("content"); ?>
    <?php $__env->startPush('head'); ?>
        <link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
        <style>
            .select2-container--default .select2-selection--single {
                border-radius: 0px !important
            }

            .select2-container .select2-selection--single {
                height: 35px
            }
        </style>
    <?php $__env->stopPush(); ?>
    <?php $__env->startPush('bottom'); ?>
        <script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
        <script>
            $(function () {
                $('.select2').select2();
            })
        </script>
    <?php $__env->stopPush(); ?>

    <ul class="nav nav-tabs">
        <li role="presentation"><a href="<?php echo e(Route('ModulsControllerGetStep1')."/".$id); ?>"><i class='fa fa-info'></i> Step 1 - Module Information</a></li>
        <li role="presentation"><a href="<?php echo e(Route('ModulsControllerGetStep2')."/".$id); ?>"><i class='fa fa-table'></i> Step 2 - Table Display</a></li>
        <li role="presentation" class="active"><a href="<?php echo e(Route('ModulsControllerGetStep3')."/".$id); ?>"><i class='fa fa-plus-square-o'></i> Step 3 - Form Display</a></li>
        <li role="presentation"><a href="<?php echo e(Route('ModulsControllerGetStep4')."/".$id); ?>"><i class='fa fa-wrench'></i> Step 4 - Configuration</a></li>
    </ul>
    <?php $__env->startPush('head'); ?>
        <style>
            .table-form tbody tr td {
                position: relative;
            }

            .sub {
                position: absolute;
                top: inherit;
                left: inherit;
                padding: 0 0 0 0;
                list-style-type: none;
                height: 180px;
                overflow: auto;
                z-index: 1;
            }

            .sub li {
                padding: 5px;
                background: #eae9e8;
                cursor: pointer;
                display: block;
                width: 180px;
            }

            .sub li:hover {
                background: #ECF0F5;
            }
        </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('bottom'); ?>
        <script type="text/javascript">
            var columns = <?php echo json_encode($columns); ?>;
            var types = <?php echo json_encode($types); ?>;
            var validation_rules = ['required', 'string', 'integer', 'double', 'image', 'date', 'numeric', 'alpha_spaces'];

            function ucwords(str) {
                return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                    return $1.toUpperCase();
                });
            }

            function showTypeSuggest(t) {
                t = $(t);

                t.next("ul").remove();
                var list = '';
                $.each(types, function (i, obj) {
                    list += "<li>" + obj + "</li>";
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showTypeSuggestLike(t) {
                t = $(t);

                var v = t.val();
                t.next("ul").remove();
                if (!v) return false;

                var list = '';
                $.each(types, function (i, obj) {
                    if (obj.includes(v.toLowerCase())) {
                        list += "<li>" + obj + "</li>";
                    }
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showNameSuggest(t) {
                t = $(t);

                t.next("ul").remove();
                var list = '';
                $.each(columns, function (i, obj) {
                    list += "<li>" + obj + "</li>";
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showNameSuggestLike(t) {
                t = $(t);

                var v = t.val();
                t.next("ul").remove();
                if (!v) return false;

                var list = '';
                $.each(columns, function (i, obj) {
                    if (obj.includes(v.toLowerCase())) {
                        list += "<li>" + obj + "</li>";
                    }
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showColumnSuggest(t) {
                t = $(t);
                t.next("ul").remove();

                var list = '';
                $.each(columns, function (i, obj) {
                    obj = obj.replace('id_', '');
                    obj = ucwords(obj.replace('_', ' '));
                    list += "<li>" + obj + "</li>";
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showColumnSuggestLike(t) {
                t = $(t);
                var v = t.val();

                t.next("ul").remove();
                if (!v) return false;

                var list = '';
                $.each(columns, function (i, obj) {

                    if (obj.includes(v.toLowerCase())) {
                        obj = obj.replace('id_', '');
                        obj = ucwords(obj.replace('_', ' '));

                        list += "<li>" + obj + "</li>";
                    }
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showValidationSuggest(t) {
                t = $(t);
                t.next("ul").remove();

                var list = '';
                $.each(validation_rules, function (i, obj) {
                    list += "<li>" + obj + "</li>";
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showValidationSuggestLike(t) {
                t = $(t);
                var v = t.val();

                t.next("ul").remove();
                if (!v) return false;

                var list = '';
                $.each(validation_rules, function (i, obj) {
                    if (obj.includes(v.toLowerCase())) {
                        list += "<li>" + obj + "</li>";
                    }
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            $(function () {


                $(document).on('click', '.btn-plus', function () {
                    var tr_parent = $(this).parent().parent('tr');
                    var clone = $('#tr-sample').clone();
                    clone.removeAttr('id');
                    tr_parent.after(clone);
                    $('.table-form tr').not('#tr-sample').show();
                })

                //init row
                $('.btn-plus').last().click();

                $(document).mouseup(function (e) {
                    var container = $(".sub");
                    if (!container.is(e.target)
                        && container.has(e.target).length === 0) {
                        container.hide();
                    }
                });

                $(document).on('click', '.sub li', function () {
                    var v = $(this).text();
                    var t = $(this).parent('ul').parent('td');
                    var tr_index = parseInt(t.parent().index());
                    console.log(tr_index);

                    var input_name = $(this).parent().parent('td').find('input[type=text]').attr('name');

                    if (input_name == 'type[]') {
                        $(this).parent('ul').prev('input[type=text]').val(v);
                        $(this).parent('ul').remove();

                        t.parent('tr').find('.option_area').empty();

                        $.getJSON("<?php echo e(CRUDBooster::mainpath('type-info')); ?>/" + v, function (data) {

                            if (data.alert) {
                                t.parent('tr').find('.option_area').prepend("<div class='alert alert-warning'><strong>IMPORTANT</strong><br/>" + data.alert + "</div>");
                            }

                            if (data.attribute.required) {
                                $.each(data.attribute.required, function (key, val) {

                                    var form_group_html = '';

                                    if (val instanceof Object) {
                                        form_group_html += "<div class='form-group'><label>" + key + "</label>";

                                        if (val.type) {
                                            if (val.type == 'radio') {
                                                $.each(val.enum, function (i, o) {
                                                    form_group_html += "<input type='radio' name='option[" + tr_index + "][" + key + "]' value='" + o + "'/> " + o + " &nbsp;&nbsp;";
                                                })
                                            } else {
                                                if (val.type == 'array') {

                                                    form_group_html += "<input class='form-control required' name='option[" + tr_index + "][" + key + "]' placeholder='" + val.placeholder + "' type='text'/>";
                                                    form_group_html += "<input name='option[" + tr_index + "][" + key + "_type]' value='array' type='hidden'/>";

                                                } else {

                                                    form_group_html += "<input class='form-control required' name='option[" + tr_index + "][" + key + "]' placeholder='" + val.placeholder + "' type='text'/>";
                                                }
                                            }
                                        } else {
                                            form_group_html += "<input class='form-control required' name='option[" + tr_index + "][" + key + "]' placeholder='" + val + "' type='text'/>";
                                        }

                                        form_group_html += "</div>";
                                    } else {
                                        form_group_html +=
                                            "<div class='form-group'>" +
                                            "<label>" + key + "</label>" +
                                            "<input class='form-control required' name='option[" + tr_index + "][" + key + "]' placeholder='" + val + "' type='text'/>" +
                                            "</div>"
                                        ;
                                    }

                                    t.parent('tr').find('.option_area').append(form_group_html);

                                });
                            }

                            if (data.attribute.requiredOne) {
                                $.each(data.attribute.requiredOne, function (key, val) {
                                    t.parent('tr').find('.option_area').append(
                                        "<div class='form-group'>" +
                                        "<label>" + key + "</label>" +
                                        "<input class='form-control required-one'  name='option[" + tr_index + "][" + key + "]' placeholder='" + val + "' type='text'/>" +
                                        "</div>"
                                    );
                                });
                            }

                            if (data.attribute.optional) {
                                $.each(data.attribute.optional, function (key, val) {
                                    if (typeof(val) == "object") {
                                        if (val.type == 'textarea') {
                                            t.parent('tr').find('.option_area').append(
                                                "<div class='form-group'>" +
                                                "<label>" + key + "</label>" +
                                                "<textarea class='form-control' name='option[" + tr_index + "][" + key + "]' placeholder='" + val.placeholder + "' ></textarea>" +
                                                "</div>"
                                            );
                                        }
                                    } else {
                                        t.parent('tr').find('.option_area').append(
                                            "<div class='form-group'>" +
                                            "<label>" + key + "</label>" +
                                            "<input class='form-control' name='option[" + tr_index + "][" + key + "]' placeholder='" + val + "' type='text'/>" +
                                            "</div>"
                                        );
                                    }
                                });
                            }
                        })

                    } else if (input_name == 'validation[]') {
                        var currentVal = $(this).parent('ul').prev('input[type=text]').val();
                        if (currentVal != '') {
                            v = currentVal + '|' + v;
                        }
                        $(this).parent('ul').prev('input[type=text]').val(v);
                        $(this).parent('ul').remove();
                    } else {
                        $(this).parent('ul').prev('input[type=text]').val(v);
                        $(this).parent('ul').remove();
                    }
                })

                $(document).on('click', '.table-form .btn-delete', function () {
                    $(this).parent().parent().remove();
                })

                $(document).on('click', '.table-form .btn-up', function () {
                    var tr = $(this).parent().parent();
                    var trPrev = tr.prev('tr');
                    if (trPrev.length != 0) {

                        tr.prev('tr').before(tr.clone());
                        tr.remove();
                    }
                })

                $(document).on('click', '.table-form .btn-down', function () {
                    var tr = $(this).parent().parent();
                    var trPrev = tr.next('tr');
                    if (trPrev.length != 0) {

                        tr.next('tr').after(tr.clone());
                        tr.remove();
                    }
                })

                var current_option_area = null;

                $(document).on('click', '.btn-options', function () {
                    $('#myModal .modal-body').empty();

                    current_option_area = $(this).next('.option_area');

                    var clone = $(this).next('.option_area').clone();
                    clone.removeAttr('style');
                    clone.appendTo('#myModal .modal-body');

                    $('#myModal').modal('show');
                })

                $('#myModal .btn-save-option').click(function () {

                    //Validation
                    var i_required = [];
                    $('#myModal .modal-body .required').each(function () {
                        var value = $(this).val();
                        var name = $(this).attr('name');
                        if (value == '') {
                            i_required.push(name);
                        }
                    });

                    if (i_required.length > 0) {
                        console.log(i_required);
                        alert("Some these fields are required : " + i_required.join(", "));
                        return false;
                    }

                    //Validation
                    var i_required_one = [];
                    $('#myModal .modal-body .required-one').each(function () {
                        var value = $(this).val();
                        var name = $(this).attr('name');
                        if (value == '') {
                            i_required_one.push(name);
                        }
                    })

                    if (i_required_one.length > 0 && i_required_one.length == $('#myModal .modal-body .required-one').length) {
                        alert("One of these fields are required : " + i_required_one.join(", "));
                        return false;
                    }

                    current_option_area.empty();
                    var clone = $('#myModal .option_area').children().clone();
                    current_option_area.html(clone);
                    $('#myModal .modal-body').empty();
                    $('#myModal').modal('hide');
                })

            })
        </script>
    <?php $__env->stopPush(); ?>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class='fa fa-cog'></i> Options</h4>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn-save-option btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Form Display</h3>
        </div>
        <div class="box-body">
            <form method="post" autocomplete="off" action="<?php echo e(Route('ModulsControllerPostStep4')); ?>">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="id" value="<?php echo e($id); ?>">

                <table class='table-form table table-striped'>
                    <thead>
                    <tr>
                        <th>Label</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Validation</th>
                        <th width="90px">Width</th>
                        <th width="100px">Options</th>
                        <th width="180px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $index = 0;?>
                    <?php $__currentLoopData = $cb_form; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><input type='text' value='<?php echo e($form["label"]); ?>' placeholder="Input field label" onclick='showColumnSuggest(this)'
                                       onkeyup="showColumnSuggestLike(this)" class='form-control labels' name='label[]'/></td>
                            <td><input type='text' value='<?php echo e($form["name"]); ?>' placeholder="Input field name" onclick='showNameSuggest(this)'
                                       onkeyup="showNameSuggestLike(this)" class='form-control name' name='name[]'/></td>
                            <td><input type='text' value='<?php echo e($form["type"]?:"text"); ?>' placeholder="Input field type" onclick='showTypeSuggest(this)'
                                       onkeyup="showTypeSuggestLike(this)" class='form-control type' name='type[]'/></td>
                            <td><input type='text' value='<?php echo e($form["validation"]); ?>' class='form-control validation' onclick="showValidationSuggest(this)"
                                       onkeyup="showValidationSuggestLike(this)" name='validation[]' value='required' placeholder='Enter Laravel Validation'/>
                            </td>
                            <td>
                                <select class='form-control width' name='width[]'>
                                    <?php for($i=10;$i>=1;$i--): ?>
                                        <option <?php echo e(($form['width'] == "col-sm-$i")?"selected":""); ?> value='col-sm-<?php echo e($i); ?>'><?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <a class='btn btn-primary btn-options' href='javascript:;'><i class='fa fa-cog'></i> Options</a>
                                <div class='option_area' style="display: none">
                                    <?php

                                    $type = $form["type"] ?: "text";
                                    $types = base_path('vendor/crocodicstudio/crudbooster/src/views/default/type_components/'.$type.'/info.json');
                                    $types = file_get_contents($types);
                                    $types = json_decode($types);

                                    if($types):
                                    ?>

                                    <?php if($types->alert): ?>
                                        <div class="alert alert-warning">
                                            <?php echo $types->alert; ?>

                                        </div>
                                    <?php endif; ?>

                                    <?php
                                    if($types->attribute->required):
                                    foreach($types->attribute->required as $key=>$val):
                                    @$value = $form[$key];
                                    if(is_object($val)):

                                    if($val->type && $val->type == 'radio'):
                                    ?>
                                    <div class="form-group">
                                        <label><?php echo e($key); ?></label>
                                        <?php $__currentLoopData = $val->enum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <input type="radio" name="option[<?php echo e($index); ?>][<?php echo e($key); ?>]"
                                                   <?php echo e(($enum == $value)?"checked":""); ?> value="<?php echo e($enum); ?>"> <?php echo e($enum); ?>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </div>

                                    <?php else:?>

                                    <div class="form-group">
                                        <label><?php echo e($key); ?></label>
                                        <input type="text" name="option[<?php echo e($index); ?>][<?php echo e($key); ?>]" placeholder="<?php echo e($val->placeholder); ?>" value="<?php echo e($value); ?>"
                                               class="form-control">
                                    </div>
                                    <?php endif;?>
                                    <?php else:?>

                                    <div class="form-group">
                                        <label><?php echo e($key); ?></label>
                                        <input type="text" name="option[<?php echo e($index); ?>][<?php echo e($key); ?>]" placeholder="<?php echo e($val); ?>" value="<?php echo e($value); ?>" class="form-control">
                                    </div>

                                    <?php endif;?>
                                    <?php endforeach; endif;?>



                                    <?php
                                    if($types->attribute->requiredOne):
                                    foreach($types->attribute->requiredOne as $key=>$val):
                                    @$value = $form[$key];
                                    ?>
                                    <div class="form-group">
                                        <label><?php echo e($key); ?></label>
                                        <input type="text" name="option[<?php echo e($index); ?>][<?php echo e($key); ?>]" placeholder="<?php echo e($val); ?>" value="<?php echo e($value); ?>" class="form-control">
                                    </div>
                                    <?php endforeach; endif;?>

                                    <?php
                                    if($types->attribute->optional):
                                    foreach($types->attribute->optional as $key=>$val):
                                    @$value = $form[$key];

                                    ?>
                                    <div class="form-group">
                                        <label><?php echo e($key); ?></label>
                                        <?php if(is_object($val) && property_exists($val, 'type') && $val->type == 'textarea'): ?>
                                            <textarea type="text" name="option[<?php echo e($index); ?>][<?php echo e($key); ?>]" placeholder="<?php echo e($val->placeholder); ?>"
                                                      class="form-control"><?php echo e($value); ?></textarea>
                                        <?php else: ?>
                                            <input type="text" name="option[<?php echo e($index); ?>][<?php echo e($key); ?>]" placeholder="<?php echo e($val); ?>" value="<?php echo e($value); ?>"
                                                   class="form-control">
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; endif;?>


                                    <?php endif;?>
                                </div>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-info btn-plus"><i class='fa fa-plus'></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-delete"><i class='fa fa-trash'></i></a>
                                <a href="javascript:void(0)" class="btn btn-success btn-up"><i class='fa fa-arrow-up'></i></a>
                                <a href="javascript:void(0)" class="btn btn-success btn-down"><i class='fa fa-arrow-down'></i></a>
                            </td>
                        </tr>
                        <?php $index++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr id='tr-sample' style="display: none">
                        <td><input type='text' placeholder="Input field label" onclick='showColumnSuggest(this)' onkeyup="showColumnSuggestLike(this)"
                                   class='form-control labels' name='label[]'/></td>
                        <td><input type='text' placeholder="Input field name" onclick='showNameSuggest(this)' onkeyup="showNameSuggestLike(this)"
                                   class='form-control name' name='name[]'/></td>
                        <td><input type='text' placeholder="Input field type" onclick='showTypeSuggest(this)' onkeyup="showTypeSuggestLike(this)"
                                   class='form-control type' name='type[]'/></td>
                        <td><input type='text' class='form-control validation' onclick="showValidationSuggest(this)" onkeyup="showValidationSuggestLike(this)"
                                   name='validation[]' value='required' placeholder='Enter Laravel Validation'/></td>
                        <td>
                            <select class='form-control width' name='width[]'>
                                <?php for($i=10;$i>=1;$i--): ?>
                                    <option <?php echo e(($i==9)?"selected":""); ?> value='col-sm-<?php echo e($i); ?>'><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td>
                            <a class='btn btn-primary btn-options' href='#'><i class='fa fa-cog'></i> Options</a>
                            <div class='option_area' style="display: none">

                            </div>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-info btn-plus"><i class='fa fa-plus'></i></a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-delete"><i class='fa fa-trash'></i></a>
                            <a href="javascript:void(0)" class="btn btn-success btn-up"><i class='fa fa-arrow-up'></i></a>
                            <a href="javascript:void(0)" class="btn btn-success btn-down"><i class='fa fa-arrow-down'></i></a>
                        </td>
                    </tr>


                    </tbody>
                </table>

        </div>
        <div class="box-footer">
            <div align="right">
                <button type="button" onclick="location.href='<?php echo e(CRUDBooster::mainpath('step2').'/'.$id); ?>'" class="btn btn-default">&laquo; Back</button>
                <input type="submit" class="btn btn-primary" value="Step 4 &raquo;">
            </div>
        </div>
        </form>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/module_generator/step3.blade.php ENDPATH**/ ?>