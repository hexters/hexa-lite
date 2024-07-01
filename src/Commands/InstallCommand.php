<?php

namespace Hexters\HexaLite\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\{warning, info};

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hexa:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install hexa setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if (!$this->hasPackage('filament/filament')) {
            warning("You haven't installed Filament yet, follow the commands below:");
            info('composer require filament/filament');
            info('php artisan filament:install --panels');
            info('php artisan hexa:install');
            exit();
        }

        $this->call('vendor:publish', ['--tag' => 'filament-hexa', '--force' => true]);
    }

    protected function hasPackage($package)
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        return array_key_exists($package, $composer['require'] ?? [])
            || array_key_exists($package, $composer['require-dev'] ?? []);
    }
}
