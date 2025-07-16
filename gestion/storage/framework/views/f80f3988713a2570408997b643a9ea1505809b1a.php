<?php
$ext = pathinfo($value, PATHINFO_EXTENSION);
$images_type = array('jpg', 'png', 'gif', 'jpeg', 'bmp', 'tiff');
if(Storage::exists($value) || file_exists($value)):
if(in_array(strtolower($ext), $images_type)):?>
<a data-lightbox='roadtrip' href='<?php echo e(asset($value)); ?>'><img style='max-width:150px' title="Image For <?php echo e($form['label']); ?>" src='<?php echo e(asset($value)); ?>'/></a>
<?php else:?>
<a href='<?php echo e(asset($value)); ?>?download=1' target="_blank"><?php echo e(cbLang("button_download_file")); ?>: <?php echo e(basename($value)); ?> <i class="fa fa-download"></i></a>
<?php endif;?>
<?php endif;?>
<?php /**PATH /home/u923093801/domains/tuzonacomercial.com/public_html/sarov/vendor/crocodicstudio/crudbooster/src/views/default/type_components/upload/component_detail.blade.php ENDPATH**/ ?>