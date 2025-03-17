<?php

namespace Fenixthelord\LaravelLicense\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
     */
    public function handle()
    {
        // سؤال المستخدم
        $choice = $this->choice(
            'Do you want to use the package as a Client or Server?',
            ['client', 'server'],
            0
        );

        // نشر ملف الإعدادات المشترك
        $this->call('vendor:publish', ['--tag' => 'laravel-license-config']);

        if ($choice === 'server') {
            $this->info('Setting up for Server mode...');
            $this->call('vendor:publish', ['--tag' => 'laravel-license-migrations']);
            $this->call('migrate');
        } else {
            $this->info('Setting up for Client mode...');
            $this->setupClientMode();
        }
    }

    /**
     * إعداد وضع العميل.
     */
    protected function setupClientMode()
    {
        // نشر وإضافة Middleware تلقائيًا
        $this->call('vendor:publish', ['--tag' => 'laravel-license-middleware']);
        $this->addMiddlewareToKernel();

        // تسجيل Service
        $this->registerService();
    }

    /**
     * إضافة Middleware إلى `Kernel.php`
     */
    protected function addMiddlewareToKernel()
    {
        $kernelPath = app_path('Http/Kernel.php');

        if (File::exists($kernelPath)) {
            $kernelContent = File::get($kernelPath);
            $middlewareLine = "\\Fenixthelord\\LaravelLicense\\Http\\Middleware\\CheckLicense::class,";

            if (!str_contains($kernelContent, $middlewareLine)) {
                $this->info('Adding License Middleware to Kernel...');
                $updatedContent = str_replace(
                    "protected \$middleware = [",
                    "protected \$middleware = [\n        $middlewareLine",
                    $kernelContent
                );
                File::put($kernelPath, $updatedContent);
            } else {
                $this->info('License Middleware already exists in Kernel.');
            }
        } else {
            $this->error('Kernel.php not found! Middleware not added.');
        }
    }

    /**
     * تسجيل Service في `config/app.php`
     */
    protected function registerService()
    {
        $configPath = config_path('app.php');

        if (File::exists($configPath)) {
            $configContent = File::get($configPath);
            $serviceProvider = "Fenixthelord\\LaravelLicense\\Providers\\LicenseServiceProvider::class,";

            if (!str_contains($configContent, $serviceProvider)) {
                $this->info('Registering LicenseServiceProvider...');
                $updatedContent = str_replace(
                    "'providers' => [",
                    "'providers' => [\n        $serviceProvider",
                    $configContent
                );
                File::put($configPath, $updatedContent);
            } else {
                $this->info('LicenseServiceProvider is already registered.');
            }
        } else {
            $this->error('app.php not found! ServiceProvider not registered.');
        }
    }
}
