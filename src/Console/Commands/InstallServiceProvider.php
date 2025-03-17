<?php

namespace Fenixthelord\License\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallServiceProvider extends Command
{
    protected $signature = 'install:license-package';
    protected $description = 'Install the License package and register the AppServiceProvider automatically.';

    public function handle()
    {
        // مسار ملف config/app.php
        $appConfigPath = base_path('config/app.php');

        // التحقق من وجود "AppServiceProvider" في الملف
        if (File::exists($appConfigPath)) {
            $config = require $appConfigPath;

            // التحقق إذا كان المزود موجودًا بالفعل
            if (!in_array(\Fenixthelord\License\Providers\AppServiceProvider::class, $config['providers'])) {
                // إضافة المزود إلى مصفوفة providers
                $config['providers'][] = \Fenixthelord\License\Providers\AppServiceProvider::class;

                // إعادة كتابة الملف مع التعديل
                File::put($appConfigPath, '<?php return ' . var_export($config, true) . ';');

                $this->info('AppServiceProvider has been registered successfully!');
            } else {
                $this->info('AppServiceProvider is already registered.');
            }
        } else {
            $this->error('Could not find config/app.php.');
        }
    }
}
