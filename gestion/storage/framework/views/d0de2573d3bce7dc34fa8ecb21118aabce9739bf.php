<?php if($command=='layout'): ?>
    <div id='<?php echo e($componentID); ?>' class='border-box'>

        <div class="small-box [color]">
            <div class='inner inner-box'>
                <h3>[sql]</h3>
                <p>[name]</p>
            </div>
            <div class="icon">
                <i class="ion [icon]"></i>
            </div>
            <a href="[link]" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>

        <div class='action pull-right'>
            <a href='javascript:void(0)' data-componentid='<?php echo e($componentID); ?>' data-name='Small Box' class='btn-edit-component'><i class='fa fa-pencil'></i></a>
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
            <label>Icon By Ionicons</label>
            <input class="form-control" required name='config[icon]' type='text' value='<?php echo e(@$config->icon); ?>'/>
            E.g : ion-bag . You can find more icon, checkout at <a target='_blank' href='http://ionicons.com/'>ionicons.com</a>
        </div>

        <div class="form-group">
            <label>Color</label>
            <select class='form-control' required name='config[color]'>
                <option <?php echo e((@$config->color == 'bg-green')?"selected":""); ?> value='bg-green'>Green</option>
                <option <?php echo e((@$config->color == 'bg-red')?"selected":""); ?> value='bg-red'>Red</option>
                <option <?php echo e((@$config->color == 'bg-aqua')?"selected":""); ?> value='bg-aqua'>Aqua</option>
                <option <?php echo e((@$config->color == 'bg-yellow')?"selected":""); ?> value='bg-yellow'>Yellow</option>
            </select>
        </div>

        <div class="form-group">
            <label>Link</label>
            <input class="form-control" required name='config[link]' type='text' value='<?php echo e(@$config->link); ?>'/>
        </div>

        <div class="form-group">
            <label>Count (SQL QUERY)</label>
            <textarea name='config[sql]' rows="5" class='form-control'><?php echo e(@$config->sql); ?></textarea>
            <div class="help-block">Make sure the sql query are correct unless the widget will be broken. Mak sure give the alias name each column. You may use
                alias [SESSION_NAME] to get the session
            </div>
        </div>

    </form>
<?php elseif($command=='showFunction'): ?>
    <?php
    if ($key == 'sql') {
        try {
            $sessions = Session::all();
            foreach ($sessions as $key => $val) {
                $value = str_replace("[".$key."]", $val, $value);
            }
            echo reset(DB::select(DB::raw($value))[0]);
        } catch (\Exception $e) {
            echo 'ERROR';
        }
    } else {
        echo $value;
    }

    ?>
<?php endif; ?>	<?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/statistic_builder/components/smallbox.blade.php ENDPATH**/ ?>