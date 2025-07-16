<?php $__env->startSection('content'); ?>

    <div>

        <?php if(CRUDBooster::getCurrentMethod() != 'getProfile' && $button_cancel): ?>
            <?php if(g('return_url')): ?>
                <p><a title='Return' href='<?php echo e(g("return_url")); ?>'><i class='fa fa-chevron-circle-left '></i>
                        &nbsp; <?php echo e(cbLang("form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])); ?></a></p>
            <?php else: ?>
                <p><a title='Main Module' href='<?php echo e(CRUDBooster::mainpath()); ?>'><i class='fa fa-chevron-circle-left '></i>
                        &nbsp; <?php echo e(cbLang("form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])); ?></a></p>
            <?php endif; ?>
        <?php endif; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><i class='<?php echo e(CRUDBooster::getCurrentModule()->icon); ?>'></i> <?php echo $page_title; ?></strong>
            </div>

            <div class="panel-body" style="padding:20px 0px 0px 0px">
                <?php
                $action = (@$row) ? CRUDBooster::mainpath("edit-save/$row->id") : CRUDBooster::mainpath("add-save");
                $return_url = ($return_url) ?: g('return_url');
                ?>
                <form class='form-horizontal' method='post' id="form" enctype="multipart/form-data" action='<?php echo e($action); ?>'>
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type='hidden' name='return_url' value='<?php echo e(@$return_url); ?>'/>
                    <input type='hidden' name='ref_mainpath' value='<?php echo e(CRUDBooster::mainpath()); ?>'/>
                    <input type='hidden' name='ref_parameter' value='<?php echo e(urldecode(http_build_query(@$_GET))); ?>'/>
                    <?php if($hide_form): ?>
                        <input type="hidden" name="hide_form" value='<?php echo serialize($hide_form); ?>'>
                    <?php endif; ?>
                    <div class="box-body" id="parent-form-area">

                        <?php if($command == 'detail'): ?>
                            <?php echo $__env->make("crudbooster::default.form_detail", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php else: ?>
                            <?php echo $__env->make("crudbooster::default.form_body", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    </div><!-- /.box-body -->

                    <div class="box-footer" style="background: #F5F5F5">

                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-10">
                                <?php if($button_cancel && CRUDBooster::getCurrentMethod() != 'getDetail'): ?>
                                    <?php if(g('return_url')): ?>
                                        <a href='<?php echo e(g("return_url")); ?>' class='btn btn-default'><i
                                                    class='fa fa-chevron-circle-left'></i> <?php echo e(cbLang("button_back")); ?></a>
                                    <?php else: ?>
                                        <a href='<?php echo e(CRUDBooster::mainpath("?".http_build_query(@$_GET))); ?>' class='btn btn-default'><i
                                                    class='fa fa-chevron-circle-left'></i> <?php echo e(cbLang("button_back")); ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if(CRUDBooster::isCreate() || CRUDBooster::isUpdate()): ?>

                                    <?php if(CRUDBooster::isCreate() && $button_addmore==TRUE && $command == 'add'): ?>
                                        <input type="submit" name="submit" value='<?php echo e(cbLang("button_save_more")); ?>' class='btn btn-success'>
                                    <?php endif; ?>

                                    <?php if($button_save && $command != 'detail'): ?>
                                        <input type="submit" name="submit" value='<?php echo e(cbLang("button_save")); ?>' class='btn btn-success'>
                                    <?php endif; ?>

                                <?php endif; ?>
                            </div>
                        </div>


                    </div><!-- /.box-footer-->

                </form>

            </div>
        </div>
    </div><!--END AUTO MARGIN-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('crudbooster::admin_template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u923093801/domains/tuzonacomercial.com/public_html/sarov/vendor/crocodicstudio/crudbooster/src/views/default/form.blade.php ENDPATH**/ ?>