<?php

namespace Fenixthelord\License\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;


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
            $this->setupServerMode();
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
        //$this->call('vendor:publish', ['--tag' => 'laravel-license-middleware']);
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
            $middlewareLine = "\\Fenixthelord\\License\\Http\\Middleware\\CheckLicense::class,";

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
            $serviceProvider = "Fenixthelord\\License\\Providers\\LicenseServiceProvider::class,";

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

    /**
     * إعداد وضع السيرفر.
     */
    protected function setupServerMode()
    {
        // نشر الميجريشنز
        $this->call('vendor:publish', ['--tag' => 'laravel-license-migrations']);
        $this->call('migrate');

        // نشر الكنترولر يدويًا (لأن Laravel لا يدعمه تلقائيًا)
        //$this->publishController();

        // نشر التوجيهات (routes/api.php)
        //$this->publishRoutes();

        // نشر النموذج
        //$this->publishModel();

        // التحقق من Filament وتثبيته إذا كان غير مثبت
        //$this->installFilament();
    }

    /**
     * نشر `LicenseController.php` يدويًا داخل `app/Http/Controllers/`
     */
    protected function publishController()
    {
        $controllerSource = __DIR__ . '/../../Http/Controllers/LicenseController.php';
        $controllerDestination = app_path('Http/Controllers/LicenseController.php');

        if (!File::exists($controllerDestination)) {
            File::copy($controllerSource, $controllerDestination);
            $this->info('LicenseController has been published.');
        } else {
            $this->info('LicenseController already exists, skipping.');
        }
    }

    /**
     * نشر `routes/api.php`
     */
    protected function publishRoutes()
    {
        $routesSource = __DIR__ . '/../../../routes/api.php';
        $routesDestination = base_path('routes/license_api.php');

        if (!File::exists($routesDestination)) {
            File::copy($routesSource, $routesDestination);
            $this->info('License API routes have been published.');
        } else {
            $this->info('License API routes already exist, skipping.');
        }
    }

    protected function publishModel()
    {
        $modelSource = __DIR__ . '/../../Models/License.php';
        $modelDestination = app_path('Models/License.php');

        if (!File::exists($modelDestination)) {
            File::copy($modelSource, $modelDestination);
            $this->info('License Model has been published.');
        } else {
            $this->info('License Model already exists, skipping.');
        }
    }

    /**
     * التحقق من Filament وتثبيته إذا لم يكن مثبتًا.
     */
    protected function installFilament()
    {
        // تحقق إذا كان Filament مثبتًا بالفعل
        if (!class_exists(\Filament\FilamentServiceProvider::class)) {
            $this->info('Filament not found. Skipping Filament installation...');
            return;
        }

        // تثبيت Filament والتأكد من عمله بشكل سليم
        $this->info('Filament found. Installing Filament...');

        // تشغيل Filament install بدون تفاعل
        $this->call('filament:install', ['--no-interaction' => true]);

        // تثبيت Panels بدون الحاجة إلى `--provider`
        $this->call('filament:install', ['--panels' => true]);


        // نشر إعدادات Filament
        Artisan::call('vendor:publish', ['--tag' => 'filament-config']);

        // حذف الكاش وإعادة تحميله لضمان تسجيل المسارات
        Artisan::call('optimize:clear');
        Artisan::call('route:clear');

        // تسجيل `LicensePanelProvider` يدويًا إذا كان غير موجود
        //$this->registerPanelProvider();

        // التحقق من تسجيل Routes الخاصة بـ Filament
        if (!Route::has('login')) {
            $this->warn('Filament routes not loaded properly. Attempting to fix...');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        }

        // إنشاء مستخدم Filament افتراضي
        Artisan::call('make:filament-user', [
            '--name' => 'Muhammad',
            '--email' => 'fenixthelord@gmail.com',
            '--password' => '12345678'
        ]);

        $this->info('Filament user create successfuly.');
        $this->info('Filament setup completed.');
    }

    /**
     * تسجيل `LicensePanelProvider` في حالة عدم توفره.
     */
    protected function registerPanelProvider()
    {
        $configPath = config_path('app.php');

        if (File::exists($configPath)) {
            $configContent = File::get($configPath);
            $providerClass = "App\\Providers\\LicensePanelProvider::class,";

            if (!str_contains($configContent, $providerClass)) {
                $this->info('Registering LicensePanelProvider...');
                $updatedContent = str_replace(
                    "'providers' => [",
                    "'providers' => [\n        $providerClass",
                    $configContent
                );
                File::put($configPath, $updatedContent);
            } else {
                $this->info('LicensePanelProvider is already registered.');
            }
        } else {
            $this->error('app.php not found! Provider registration skipped.');
        }
    }

}
