<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "🔧 Laravel Database Setup Script\n";
echo "================================\n\n";

try {
    // Load Laravel application
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel application loaded successfully\n";
    
    // Test database connection
    try {
        DB::connection()->getPdo();
        echo "✅ Database connection successful\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        echo "Please check your .env file and database configuration\n";
        exit(1);
    }
    
    // Check if forms table exists
    if (Schema::hasTable('forms')) {
        echo "✅ Forms table exists\n";
    } else {
        echo "⚠️  Forms table does not exist. Running migrations...\n";
        
        try {
            Artisan::call('migrate', ['--force' => true]);
            echo "✅ Migrations completed successfully\n";
        } catch (Exception $e) {
            echo "❌ Migration failed: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
    // Check if users table exists
    if (Schema::hasTable('users')) {
        echo "✅ Users table exists\n";
    } else {
        echo "❌ Users table does not exist. This is required for the application.\n";
        exit(1);
    }
    
    echo "\n🎉 Database setup completed successfully!\n";
    echo "You can now start the Laravel server with: php artisan serve\n";
    
} catch (Exception $e) {
    echo "❌ Setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
