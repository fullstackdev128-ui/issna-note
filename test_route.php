<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/etudiants', 'GET');
try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() == 404) {
        echo "404 NOT FOUND from Laravel kernel handling!\n";
    }
} catch (Exception $e) {
    echo "Exception: " . get_class($e) . "\n";
    echo $e->getMessage() . "\n";
}
