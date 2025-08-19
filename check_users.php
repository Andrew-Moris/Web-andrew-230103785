<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::all(['id', 'name', 'email', 'google_id', 'created_at']);

echo "Total users: " . $users->count() . "\n";
echo "Users data:\n";
foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Google ID: {$user->google_id}, Created: {$user->created_at}\n";
}

// Check specifically for Google users
$googleUsers = App\Models\User::whereNotNull('google_id')->get(['id', 'name', 'email', 'google_id', 'created_at']);
echo "\nGoogle users: " . $googleUsers->count() . "\n";
foreach ($googleUsers as $user) {
    echo "Google User - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Google ID: {$user->google_id}, Created: {$user->created_at}\n";
}