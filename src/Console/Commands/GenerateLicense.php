<?php

namespace Fenixthelord\License\Console\Commands;

use Illuminate\Console\Command;
use Fenixthelord\License\Models\License;

class GenerateLicense extends Command
{
    protected $signature = 'license:generate 
        {--days=365 : Validity period in days}
        {--domain= : Associated domain}';
    
    protected $description = 'Generate a new license key';

    public function handle()
    {
        $license = License::create([
            'key' => bin2hex(random_bytes(32)),
            'domain' => $this->option('domain') ?? config('app.url'),
            'valid_until' => now()->addDays($this->option('days'))
        ]);

        $this->info("License generated successfully:");
        $this->line("Key: ".$license->key);
        $this->line("Valid until: ".$license->valid_until->format('Y-m-d'));
    }
}
