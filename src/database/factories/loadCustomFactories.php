<?php
$factories = glob(app_path('Http/Controllers/*/database/factories/*.php')) + glob(database_path('factories/*.php'));
foreach ($factories as $file) {
    require $file;
}
