<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
foreach(User::all() as $user) {
    echo $user->email . ": " . implode(',', $user->getRoleNames()->toArray()) . "\n";
}
