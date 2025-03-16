<?php

namespace Fenixthelord\LaravelLicense\Console\Commands;

use Illuminate\Console\Command;

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
     * تنفيذ الأمر
     */
    public function handle()
    {
        // سؤال المستخدم لاختيار الوضع
        $choice = $this->choice(
            'Do you want to use the package as a Client or Server?',
            ['client', 'server'],
            0
        );

        // نشر ملف الإعدادات في كلتا الحالتين
        $this->info('Publishing configuration...');
        $this->call('vendor:publish', ['--tag' => 'laravel-license-config']);

        if ($choice === 'server') {
            $this->setupServer();
        } else {
            $this->setupClient();
        }

        $this->info('License package installed successfully!');
    }

    /**
     * ضبط الحزمة كخادم (Server)
     */
    protected function setupServer()
    {
        $this->info('Setting up for Server mode...');

        // نشر المهاجرات والموديل
        $this->call('vendor:publish', ['--tag' => 'laravel-license-migrations']);
        $this->call('vendor:publish', ['--tag' => 'laravel-license-model']);

        // تنفيذ المهاجرات
        $this->call('migrate');
    }

    /**
     * ضبط الحزمة كعميل (Client)
     */
    protected function setupClient()
    {
        $this->info('Setting up for Client mode...');
        // لا يوجد إعدادات إضافية للعميل حاليًا
    }
}
