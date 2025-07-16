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
            $(function () {
                $('select[name=table]').change(function () {
                    var v = $(this).val().replace(".", "_");
                    $.get("<?php echo e(CRUDBooster::mainpath('check-slug')); ?>/" + v, function (resp) {
                        if (resp.total == 0) {
                            $('input[name=path]').val(v);
                        } else {
                            v = v + resp.lastid;
                            $('input[name=path]').val(v);
                        }
                    })

                })
            })
        </script>
    <?php $__env->stopPush(); ?>

    <ul class="nav nav-tabs">
        <?php if($id): ?>
            <li role="presentation" class="active"><a href="<?php echo e(Route('ModulsControllerGetStep1')."/".$id); ?>"><i class='fa fa-info'></i> Step 1 - Module
                    Information</a></li>
            <li role="presentation"><a href="<?php echo e(Route('ModulsControllerGetStep2')."/".$id); ?>"><i class='fa fa-table'></i> Step 2 - Table Display</a></li>
            <li role="presentation"><a href="<?php echo e(Route('ModulsControllerGetStep3')."/".$id); ?>"><i class='fa fa-plus-square-o'></i> Step 3 - Form Display</a></li>
            <li role="presentation"><a href="<?php echo e(Route('ModulsControllerGetStep4')."/".$id); ?>"><i class='fa fa-wrench'></i> Step 4 - Configuration</a></li>
        <?php else: ?>
            <li role="presentation" class="active"><a href="#"><i class='fa fa-info'></i> Step 1 - Module Information</a></li>
            <li role="presentation"><a href="#"><i class='fa fa-table'></i> Step 2 - Table Display</a></li>
            <li role="presentation"><a href="#"><i class='fa fa-plus-square-o'></i> Step 3 - Form Display</a></li>
            <li role="presentation"><a href="#"><i class='fa fa-wrench'></i> Step 4 - Configuration</a></li>
        <?php endif; ?>
    </ul>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Module Information</h3>
        </div>
        <div class="box-body">
            <form method="post" action="<?php echo e(Route('ModulsControllerPostStep2')); ?>">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="id" value="<?php echo e($row->id); ?>">
                <div class="form-group">
                    <label for="">Table</label>
                    <select name="table" id="table" required class="select2 form-control" value="<?php echo e($row->table_name); ?>">
                        <option value=""><?php echo e(cbLang('text_prefix_option')); ?> Table</option>
                        <?php $__currentLoopData = $tables_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option <?php echo e(($table == $row->table_name)?"selected":""); ?> value="<?php echo e($table); ?>"><?php echo e($table); ?></option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="help-block">
                        Do not use cms_* as prefix on your tables name
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Module Name</label>
                    <input type="text" class="form-control" required name="name" value="<?php echo e($row->name); ?>">
                </div>

                <div class="form-group">
                    <label for="">Icon</label>
                    <select name="icon" id="icon" required class="select2 form-control">
                        <?php $__currentLoopData = $fontawesome; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php echo e(($row->icon == 'fa fa-'.$f)?"selected":""); ?> value="fa fa-<?php echo e($f); ?>"><?php echo e($f); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Module Slug</label>
                    <input type="text" class="form-control" required name="path" value="<?php echo e($row->path); ?>">
                    <div class="help-block">Please alpha numeric only, without space instead _ and or special character</div>
                </div>
        </div>
        <div class="box-footer">

            <input checked type='checkbox' name='create_menu' value='1'/> Also create menu for this module <a href='#'
                                                                                                              title='If you check this, we will create the menu for this module'>(?)</a>

            <div class='pull-right'>
                <a class='btn btn-default' href='<?php echo e(Route("ModulsControllerGetIndex")); ?>'> <?php echo e(cbLang('button_back')); ?></a>
                <input type="submit" class="btn btn-primary" value="Step 2 &raquo;">
            </div>
        </div>
        </form>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make("crudbooster::admin_template", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/module_generator/step1.blade.php ENDPATH**/ ?>