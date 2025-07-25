<?php if($command=='layout'): ?>
    <div id='<?php echo e($componentID); ?>' class='border-box'>

        <div class="panel panel-default">
            <div class="panel-heading">
                [name]
            </div>
            <div class="panel-body">
                [sql]
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
            <label>SQL Query Line</label>
            <textarea name='config[sql]' required rows="4" class='form-control'><?php echo e(@$config->sql); ?></textarea>
            <div class="block-help">
                Use column name 'label' as line chart label. Use name 'value' as value of line chart. Sparate with ; each sql line. Use [SESSION_NAME] to use
                alias session.
            </div>
        </div>

        <div class="form-group">
            <label>Line Area Name</label>
            <input class="form-control" required name='config[area_name]' type='text' value='<?php echo e(@$config->area_name); ?>'/>
            <div class="block-help">You can naming each line area. Write name sparate with ;</div>
        </div>

        <div class="form-group">
            <label>Goals Value</label>
            <input class="form-control" name='config[goals]' type='number' value='<?php echo e(@$config->goals); ?>'/>
        </div>
    </form>
<?php elseif($command=='showFunction'): ?>

    <?php if($key == 'sql'): ?>
        <?php
        $sqls = explode(';', $value);
        $dataPoints = array();
        $datax = array();

        foreach ($sqls as $i => $sql) {

            $datamerger = array();

            $sessions = Session::all();
            foreach ($sessions as $key => $val) {
                $sql = str_replace("[".$key."]", $val, $sql);
            }

            try {
                $query = DB::select(DB::raw($sql));
                foreach ($query as $r) {
                    $datax[] = $r->label;
                    $datamerger[] = $r->value;
                }
            } catch (\Exception $e) {
                echo $e;
                // echo $e->getMessage();
            }

            $dataPoints[$i] = $datamerger;
        }

        $datax = array_unique($datax);

        $area_name = explode(';', $config->area_name);
        $area_name_safe = $area_name;
        foreach ($area_name_safe as &$a) {
            $a = str_slug($a, '_');
        }

        $data_result = array();
        foreach ($datax as $i => $d) {
            $dr = array();
            $dr['y'] = $d;
            foreach ($area_name as $e => $name) {
                $name = str_slug($name, '_');
                $dr[$name] = $dataPoints[$e][$i];
            }
            $data_result[] = $dr;
        }

        $data_result = json_encode($data_result);
        // $data_result = preg_replace('/"([a-zA-Z_]+[a-zA-Z0-9_]*)":/','$1:',$data_result);

        ?>
        <div id="chartContainer-<?php echo e($componentID); ?>" style="height: 250px;"></div>


        <script type="text/javascript">

            $(function () {
                new Morris.Area({
                    element: 'chartContainer-<?php echo e($componentID); ?>',
                    data: $.parseJSON("<?php echo addslashes($data_result); ?>"),
                    xkey: 'y',
                    ykeys: <?php echo json_encode($area_name_safe); ?>,
                    labels: <?php echo json_encode($area_name); ?>,
                    parseTime: false,
                    resize: true,
                    <?php if($config->goals): ?>
                    goals: [<?php echo e($config->goals); ?>],
                    <?php endif; ?>
                    behaveLikeLine: true,
                    hideHover: 'auto'
                });
            })
        </script>


    <?php else: ?>

        <?php echo $value; ?>

    <?php endif; ?>
<?php endif; ?>	<?php /**PATH /home/u317647664/domains/gestionsarov.cloud/public_html/vendor/crocodicstudio/crudbooster/src/views/statistic_builder/components/chartarea.blade.php ENDPATH**/ ?>