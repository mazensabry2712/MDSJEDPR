<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = DB::select('DESCRIBE ppos');
foreach($columns as $col) {
    echo $col->Field . ' - ' . $col->Type . PHP_EOL;
}
