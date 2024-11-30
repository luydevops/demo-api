<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class InitService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicializa el servicio personalizado para notificaciones y colas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando configuración del servicio...');

        // Verificar si la tabla de trabajos en cola ya existe
        if (Schema::hasTable('jobs')) {
            $this->info('La tabla de colas ya existe. Saltando migración...');
        } else {
            $this->info('Creando migración para colas...');
            Artisan::call('queue:table');
            $this->info('Ejecutando migraciones...');
            Artisan::call('migrate');
            $this->info('Migraciones completadas.');
        }

        // Confirmar configuración en .env
        $this->info('Verificando configuración de QUEUE_CONNECTION...');
        $envFile = base_path('.env');
        if (file_exists($envFile)) {
            $content = file_get_contents($envFile);
            if (!str_contains($content, 'QUEUE_CONNECTION=database')) {
                file_put_contents($envFile, $content . PHP_EOL . 'QUEUE_CONNECTION=database');
                $this->info('QUEUE_CONNECTION configurado a "database" en .env.');
            } else {
                $this->info('QUEUE_CONNECTION ya está configurado.');
            }
        }

        // Finalizar configuración
        $this->info('Servicio inicializado correctamente. Recuerda iniciar el worker con: php artisan queue:work');
    }
}
