<?php
// app/Console/Commands/UpdateAgendaStatus.php

namespace App\Console\Commands;

use App\Models\Agenda;
use Illuminate\Console\Command;

class UpdateAgendaStatus extends Command
{
    protected $signature = 'agenda:update-status';
    protected $description = 'Update status agenda otomatis';

    public function handle()
    {
        Agenda::updateSemuaStatus();
        $this->info('Status agenda berhasil diupdate');
    }
}
