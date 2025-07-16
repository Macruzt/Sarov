<?php if($command=='layout'): ?>
    <div id='<?php echo e($componentID); ?>' class='border-box'>

        <div class="panel panel-default">
            <div class="panel-heading">
                [name]
            </div>
            <div class="panel-body">
                [value]
            </div>
        </div>

        <div class='action pull-right'>
            <a href='javascript:void(0)' data-componentid='<?php echo e($componentID); ?>' data-name='Panel Custom' class='btn-edit-component'><i
                        class='fa fa-pencil'></i></a> &nbsp;
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
            <label>Type</label>
            <select name='config[type]' class='form-control'>
                <option <?php echo e((@$config->type == 'controller')?"selected":""); ?> value='controller'>Controller & Method</option>
                <option <?php echo e((@$config->type == 'route')?"selected":""); ?> value='route'>Route Name</option>
            </select>
        </div>

        <div class="form-group">
            <label>Value</label>
            <input name='config[value]' type='text' class='form-control' value='<?php echo e(@$config->value); ?>'/>
            <div class='help-block'>You must enter the valid value related with current TYPE unless, widget will not work</div>
        </div>

    </form>
<?php elseif($command=='showFunction'): ?>
    <?php
    if($key == 'value') {
    if ($config->type == 'controller') {
        $url = action($value);
    } elseif ($config->type == 'route') {
        $url = route($value);
    }
    echo "<div id='content-$componentID'></div>";
    ?>

    <script>
        $(function () {
            $('#content-<?php echo e($componentID); ?>').html("<i class='fa fa-spin fa-spinner'></i> Please wait loading...");
            $.get('<?php echo e($url); ?>', function (response) {
                $('#content-<?php echo e($componentID); ?>').html(response);
            });
        })
    </script>

    <?php
    }else {
        echo $value;
    }
    ?>
<?php endif; ?>	<?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/statistic_builder/components/panelcustom.blade.php ENDPATH**/ ?>