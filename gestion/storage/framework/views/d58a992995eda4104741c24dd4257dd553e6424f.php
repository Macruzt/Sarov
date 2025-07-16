<?php $__env->startSection('content'); ?>

    <div style="width:750px;margin:0 auto ">


        <?php if(CRUDBooster::getCurrentMethod() != 'getProfile'): ?>
            <p><a href='<?php echo e(CRUDBooster::mainpath()); ?>'><?php echo e(cbLang("form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])); ?></a></p>
    <?php endif; ?>



    <!-- Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo e($page_title); ?></h3>
                <div class="box-tools">

                </div>
            </div>
            <form method='post' action='<?php echo e((@$row->id)?route("PrivilegesControllerPostEditSave")."/$row->id":route("PrivilegesControllerPostAddSave")); ?>'>
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <div class="box-body">
                    <div class="alert alert-info">
                        <strong>Note:</strong> To show the menu you have to create a menu at Menu Management
                    </div>
                    <div class='form-group'>
                        <label><?php echo e(cbLang('privileges_name')); ?></label>
                        <input type='text' class='form-control' name='name' required value='<?php echo e(@$row->name); ?>'/>
                        <div class="text-danger"><?php echo e($errors->first('name')); ?></div>
                    </div>

                    <div class='form-group'>
                        <label><?php echo e(cbLang('set_as_superadmin')); ?></label>
                        <div id='set_as_superadmin' class='radio'>
                            <label><input required <?php echo e((@$row->is_superadmin==1)?'checked':''); ?> type='radio' name='is_superadmin'
                                          value='1'/> <?php echo e(cbLang('confirmation_yes')); ?></label> &nbsp;&nbsp;
                            <label><input <?php echo e((@$row->is_superadmin==0)?'checked':''); ?> type='radio' name='is_superadmin'
                                          value='0'/> <?php echo e(cbLang('confirmation_no')); ?></label>
                        </div>
                        <div class="text-danger"><?php echo e($errors->first('is_superadmin')); ?></div>
                    </div>

                    <div class='form-group'>
                        <label><?php echo e(cbLang('chose_theme_color')); ?></label>
                        <select name='theme_color' class='form-control' required>
                            <option value=''><?php echo e(cbLang('chose_theme_color_select')); ?></option>
                            <?php
                            $skins = array(
                                'skin-blue',
                                'skin-blue-light',
                                'skin-yellow',
                                'skin-yellow-light',
                                'skin-green',
                                'skin-green-light',
                                'skin-purple',
                                'skin-purple-light',
                                'skin-red',
                                'skin-red-light',
                                'skin-black',
                                'skin-black-light'
                            );
                            foreach($skins as $skin):
                            ?>
                            <option <?=(@$row->theme_color == $skin) ? "selected" : ""?> value='<?=$skin?>'><?=ucwords(str_replace('-', ' ', $skin))?></option>
                            <?php endforeach;?>
                        </select>
                        <div class="text-danger"><?php echo e($errors->first('theme_color')); ?></div>
                        <?php $__env->startPush('bottom'); ?>
                            <script type="text/javascript">
                                $(function () {
                                    $("select[name=theme_color]").change(function () {
                                        var n = $(this).val();
                                        $("body").attr("class", n);
                                    })

                                    $('#set_as_superadmin input').click(function () {
                                        var n = $(this).val();
                                        if (n == '1') {
                                            $('#privileges_configuration').hide();
                                        } else {
                                            $('#privileges_configuration').show();
                                        }
                                    })

                                    $('#set_as_superadmin input:checked').trigger('click');
                                })
                            </script>
                        <?php $__env->stopPush(); ?>
                    </div>

                    <div id='privileges_configuration' class='form-group'>
                        <label><?php echo e(cbLang('privileges_configuration')); ?></label>
                        <?php $__env->startPush('bottom'); ?>
                            <script>
                                $(function () {
                                    $("#is_visible").click(function () {
                                        var is_ch = $(this).prop('checked');
                                        console.log('is checked create ' + is_ch);
                                        $(".is_visible").prop("checked", is_ch);
                                        console.log('Create all');
                                    })
                                    $("#is_create").click(function () {
                                        var is_ch = $(this).prop('checked');
                                        console.log('is checked create ' + is_ch);
                                        $(".is_create").prop("checked", is_ch);
                                        console.log('Create all');
                                    })
                                    $("#is_read").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_read").prop("checked", is_ch);
                                    })
                                    $("#is_edit").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_edit").prop("checked", is_ch);
                                    })
                                    $("#is_delete").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_delete").prop("checked", is_ch);
                                    })
                                    $(".select_horizontal").click(function () {
                                        var p = $(this).parents('tr');
                                        var is_ch = $(this).is(':checked');
                                        p.find("input[type=checkbox]").prop("checked", is_ch);
                                    })
                                })
                            </script>
                        <?php $__env->stopPush(); ?>
                        <table class='table table-striped table-hover table-bordered'>
                            <thead>
                            <tr class='active'>
                                <th width='3%'><?php echo e(cbLang('privileges_module_list_no')); ?></th>
                                <th width='60%'><?php echo e(cbLang('privileges_module_list_mod_names')); ?></th>
                                <th>&nbsp;</th>
                                <th><?php echo e(cbLang('privileges_module_list_view')); ?></th>
                                <th><?php echo e(cbLang('privileges_module_list_create')); ?></th>
                                <th><?php echo e(cbLang('privileges_module_list_read')); ?></th>
                                <th><?php echo e(cbLang('privileges_module_list_update')); ?></th>
                                <th><?php echo e(cbLang('privileges_module_list_delete')); ?></th>
                            </tr>
                            <tr class='info'>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_visible'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_create'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_read'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_edit'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_delete'/></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1;?>
                            <?php $__currentLoopData = $moduls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $roles = DB::table('cms_privileges_roles')->where('id_cms_moduls', $modul->id)->where('id_cms_privileges', $row->id)->first();
                                ?>
                                <tr>
                                    <td><?php echo $no++;?></td>
                                    <td><?php echo e($modul->name); ?></td>
                                    <td class='info' align="center"><input type='checkbox' title='Check All Horizontal'
                                                                           <?=($roles->is_create && $roles->is_read && $roles->is_edit && $roles->is_delete) ? "checked" : ""?> class='select_horizontal'/>
                                    </td>
                                    <td class='active' align="center"><input type='checkbox' class='is_visible' name='privileges[<?=$modul->id?>][is_visible]'
                                                                             <?=@$roles->is_visible ? "checked" : ""?> value='1'/></td>
                                    <td class='warning' align="center"><input type='checkbox' class='is_create' name='privileges[<?=$modul->id?>][is_create]'
                                                                              <?=@$roles->is_create ? "checked" : ""?> value='1'/></td>
                                    <td class='info' align="center"><input type='checkbox' class='is_read' name='privileges[<?=$modul->id?>][is_read]'
                                                                           <?=@$roles->is_read ? "checked" : ""?> value='1'/></td>
                                    <td class='success' align="center"><input type='checkbox' class='is_edit' name='privileges[<?=$modul->id?>][is_edit]'
                                                                              <?=@$roles->is_edit ? "checked" : ""?> value='1'/></td>
                                    <td class='danger' align="center"><input type='checkbox' class='is_delete' name='privileges[<?=$modul->id?>][is_delete]'
                                                                             <?=@$roles->is_delete ? "checked" : ""?> value='1'/></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                    </div>

                </div><!-- /.box-body -->
                <div class="box-footer" align="right">
                    <button type='button' onclick="location.href='<?php echo e(CRUDBooster::mainpath()); ?>'"
                            class='btn btn-default'><?php echo e(cbLang("button_cancel")); ?></button>
                    <button type='submit' class='btn btn-primary'><i class='fa fa-save'></i> <?php echo e(cbLang("button_save")); ?></button>
                </div><!-- /.box-footer-->
        </div><!-- /.box -->

    </div><!-- /.row -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('crudbooster::admin_template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/privileges.blade.php ENDPATH**/ ?>