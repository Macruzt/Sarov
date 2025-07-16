<?php $__env->startSection('content'); ?>

    <?php if($index_statistic): ?>
        <div id='box-statistic' class='row'>
            <?php $__currentLoopData = $index_statistic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="<?php echo e(($stat['width'])?:'col-sm-3'); ?>">
                    <div class="small-box bg-<?php echo e($stat['color']?:'red'); ?>">
                        <div class="inner">
                            <h3><?php echo e($stat['count']); ?></h3>
                            <p><?php echo e($stat['label']); ?></p>
                        </div>
                        <div class="icon">
                            <i class="<?php echo e($stat['icon']); ?>"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <?php if(!is_null($pre_index_html) && !empty($pre_index_html)): ?>
        <?php echo $pre_index_html; ?>

    <?php endif; ?>


    <?php if(g('return_url')): ?>
        <p><a href='<?php echo e(g("return_url")); ?>'><i class='fa fa-chevron-circle-<?php echo e(cbLang('left')); ?>'></i>
                &nbsp; <?php echo e(cbLang('form_back_to_list',['module'=>urldecode(g('label'))])); ?></a></p>
    <?php endif; ?>

    <?php if($parent_table): ?>
        <div class="box box-default">
            <div class="box-body table-responsive no-padding">
                <table class='table table-bordered'>
                    <tbody>
                    <tr class='active'>
                        <td colspan="2"><strong><i class='fa fa-bars'></i> <?php echo e(ucwords(urldecode(g('label')))); ?></strong></td>
                    </tr>
                    <?php $__currentLoopData = explode(',',urldecode(g('parent_columns'))); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td width="25%"><strong>
                                    <?php if(urldecode(g('parent_columns_alias'))): ?>
                                        <?php echo e(explode(',',urldecode(g('parent_columns_alias')))[$loop->index]); ?>

                                    <?php else: ?>
                                        <?php echo e(ucwords(str_replace('_',' ',$c))); ?>

                                    <?php endif; ?>
                                </strong></td>
                            <td> <?php echo e($parent_table->$c); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="box">
        <div class="box-header">
            <?php if($button_bulk_action && ( ($button_delete && CRUDBooster::isDelete()) || $button_selected) ): ?>
                <div class="pull-<?php echo e(cbLang('left')); ?>">
                    <div class="selected-action" style="display:inline-block;position:relative;">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                                    class='fa fa-check-square-o'></i> <?php echo e(cbLang("button_selected_action")); ?>

                            <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu">
                            <?php if($button_delete && CRUDBooster::isDelete()): ?>
                                <li><a href="javascript:void(0)" data-name='delete' title='<?php echo e(cbLang('action_delete_selected')); ?>'><i
                                                class="fa fa-trash"></i> <?php echo e(cbLang('action_delete_selected')); ?></a></li>
                            <?php endif; ?>

                            <?php if($button_selected): ?>
                                <?php $__currentLoopData = $button_selected; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $button): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><a href="javascript:void(0)" data-name='<?php echo e($button["name"]); ?>' title='<?php echo e($button["label"]); ?>'><i
                                                    class="fa fa-<?php echo e($button['icon']); ?>"></i> <?php echo e($button['label']); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                        </ul><!--end-dropdown-menu-->
                    </div><!--end-selected-action-->
                </div><!--end-pull-left-->
            <?php endif; ?>
            <div class="box-tools pull-<?php echo e(cbLang('right')); ?>" style="position: relative;margin-top: -5px;margin-right: -10px">

                <?php if($button_filter): ?>
                    <a style="margin-top:-23px" href="javascript:void(0)" id='btn_advanced_filter' data-url-parameter='<?php echo e($build_query); ?>'
                       title='<?php echo e(cbLang('filter_dialog_title')); ?>' class="btn btn-sm btn-default <?php echo e((Request::get('filter_column'))?'active':''); ?>">
                        <i class="fa fa-filter"></i> <?php echo e(cbLang("button_filter")); ?>

                    </a>
                <?php endif; ?>

                <form method='get' style="display:inline-block;width: 260px;" action='<?php echo e(Request::url()); ?>'>
                    <div class="input-group">
                        <input type="text" name="q" value="<?php echo e(Request::get('q')); ?>" class="form-control input-sm pull-<?php echo e(cbLang('right')); ?>"
                               placeholder="<?php echo e(cbLang('filter_search')); ?>"/>
                        <?php echo CRUDBooster::getUrlParameters(['q']); ?>

                        <div class="input-group-btn">
                            <?php if(Request::get('q')): ?>
                                <?php
                                $parameters = Request::all();
                                unset($parameters['q']);
                                $build_query = urldecode(http_build_query($parameters));
                                $build_query = ($build_query) ? "?".$build_query : "";
                                $build_query = (Request::all()) ? $build_query : "";
                                ?>
                                <button type='button' onclick='location.href="<?php echo e(CRUDBooster::mainpath().$build_query); ?>"'
                                        title="<?php echo e(cbLang('button_reset')); ?>" class='btn btn-sm btn-warning'><i class='fa fa-ban'></i></button>
                            <?php endif; ?>
                            <button type='submit' class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>


                <form method='get' id='form-limit-paging' style="display:inline-block" action='<?php echo e(Request::url()); ?>'>
                    <?php echo CRUDBooster::getUrlParameters(['limit']); ?>

                    <div class="input-group">
                        <select onchange="$('#form-limit-paging').submit()" name='limit' style="width: 56px;" class='form-control input-sm'>
                            <option <?php echo e(($limit==5)?'selected':''); ?> value='5'>5</option>
                            <option <?php echo e(($limit==10)?'selected':''); ?> value='10'>10</option>
                            <option <?php echo e(($limit==20)?'selected':''); ?> value='20'>20</option>
                            <option <?php echo e(($limit==25)?'selected':''); ?> value='25'>25</option>
                            <option <?php echo e(($limit==50)?'selected':''); ?> value='50'>50</option>
                            <option <?php echo e(($limit==100)?'selected':''); ?> value='100'>100</option>
                            <option <?php echo e(($limit==200)?'selected':''); ?> value='200'>200</option>
                        </select>
                    </div>
                </form>

            </div>

            <br style="clear:both"/>

        </div>
        <div class="box-body table-responsive no-padding">
            <?php echo $__env->make("crudbooster::default.table", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <?php if(!is_null($post_index_html) && !empty($post_index_html)): ?>
        <?php echo $post_index_html; ?>

    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('crudbooster::admin_template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/default/index.blade.php ENDPATH**/ ?>