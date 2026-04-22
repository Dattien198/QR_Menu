<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
foreach(User::all() as $user) {
    if ($user->getRoleNames()->isEmpty()) {
        $user->assignRole('customer');
        echo "Assigned 'customer' role to: {$user->email}\n";
    }
}
echo "Done.\n";
