<?php

namespace Fenixthelord\LaravelLicense\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallLicensePackage extends Command
{
    /**
     * اسم الأمر
     *
     * @var string
     */
    protected $signature = 'license:install';

    /**
     * وصف الأمر
     *
     * @var string
     */
    protected $description = 'Install License Package and configure as client or server';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // سؤال المستخدم
        $choice = $this->choice(
            'Do you want to use the package as a Client or Server?',
            ['client', 'server'],
            0
        );

        // بناءً على الاختيار
        if ($choice == 'server') {
            $this->info('Setting up for server...');
            // نشر الترحيلات، النماذج، والطرق الخاصة بالسيرفر
            $this->call('vendor:publish', ['--tag' => 'laravel-license-config']);
            $this->loadMigrations();
            $this->loadRoutes();
        } else {
            $this->info('Setting up for client...');
            // نشر ملفات العميل
            $this->call('vendor:publish', ['--tag' => 'laravel-license-config']);
        }
    }

    /**
     * نشر الترحيلات الخاصة بالسيرفر.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->call('migrate', ['--path' => '/../../database/migrations']);
    }

    /**
     * نشر الطرق الخاصة بالسيرفر.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        // لو كان السيرفر، نشر التوجيهات
        Artisan::call('route:cache');
    }
}
