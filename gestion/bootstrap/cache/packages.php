<?php return array (
  'barryvdh/laravel-dompdf' => 
  array (
    'providers' => 
    array (
      0 => 'Barryvdh\\DomPDF\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
    ),
  ),
  'crocodicstudio/crudbooster' => 
  array (
    'providers' => 
    array (
      0 => 'crocodicstudio\\crudbooster\\CRUDBoosterServiceProvider',
    ),
  ),
  'facade/ignition' => 
  array (
    'providers' => 
    array (
      0 => 'Facade\\Ignition\\IgnitionServiceProvider',
    ),
    'aliases' => 
    array (
      'Flare' => 'Facade\\Ignition\\Facades\\Flare',
    ),
  ),
  'fruitcake/laravel-cors' => 
  array (
    'providers' => 
    array (
      0 => 'Fruitcake\\Cors\\CorsServiceProvider',
    ),
  ),
  'intervention/image' => 
  array (
    'providers' => 
    array (
      0 => 'Intervention\\Image\\ImageServiceProvider',
    ),
    'aliases' => 
    array (
      'Image' => 'Intervention\\Image\\Facades\\Image',
    ),
  ),
  'kreait/laravel-firebase' => 
  array (
    'providers' => 
    array (
      0 => 'Kreait\\Laravel\\Firebase\\ServiceProvider',
    ),
    'aliases' => 
    array (
      'Firebase' => 'Kreait\\Laravel\\Firebase\\Facades\\Firebase',
      'FirebaseAuth' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseAuth',
      'FirebaseDatabase' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseDatabase',
      'FirebaseDynamicLinks' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseDynamicLinks',
      'FirebaseFirestore' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseFirestore',
      'FirebaseMessaging' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseMessaging',
      'FirebaseRemoteConfig' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseRemoteConfig',
      'FirebaseStorage' => 'Kreait\\Laravel\\Firebase\\Facades\\FirebaseStorage',
    ),
  ),
  'laravel/sail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sail\\SailServiceProvider',
    ),
  ),
  'laravel/sanctum' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sanctum\\SanctumServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'maatwebsite/excel' => 
  array (
    'providers' => 
    array (
      0 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
    ),
    'aliases' => 
    array (
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
);