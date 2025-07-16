<?php if($command=='layout'): ?>
    <div id='<?php echo e($componentID); ?>' class='border-box'>

        <div class="panel panel-default">
            <div class="panel-heading">
                [name]
            </div>
            <div class="panel-body">
                <p>[content]</p>
            </div>
        </div>

        <div class='action pull-right'>
            <a href='javascript:void(0)' data-componentid='<?php echo e($componentID); ?>' data-name='Panel Area' class='btn-edit-component'><i class='fa fa-pencil'></i></a>
            &nbsp;
            <a href='javascript:void(0)' data-componentid='<?php echo e($componentID); ?>' class='btn-delete-component'><i class='fa fa-trash'></i></a>
        </div>
    </div>
<?php elseif($command=='configuration'): ?>
    <form method='post'>
        <input type='hidden' name='_token' value='<?php echo e(csrf_token()); ?>'/>
        <input type='hidden' name='componentid' value='<?php echo e($componentID); ?>'/>
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" required name='config[name]' type='text' value='<?php echo e(@$config->name); ?>'/>
        </div>

        <div class="form-group">
            <label>Content</label>
            <textarea name='config[content]' required rows="10" class='form-control'><?php echo e(@$config->content); ?></textarea>
        </div>

    </form>
<?php elseif($command=='showFunction'): ?>
    <?php
    echo $value;
    ?>
<?php endif; ?>	<?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/statistic_builder/components/panelarea.blade.php ENDPATH**/ ?>