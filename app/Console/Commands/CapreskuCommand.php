<?php

namespace App\Console\Commands;

use App\Http\Controllers\PaslonController;
use App\Services\PaslonService;
use Illuminate\Console\Command;

class CapreskuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capresku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan profil paslon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paslon = new PaslonController(new PaslonService());
        $dataPaslon = $paslon->getData();
        $dataPaslon = [...$dataPaslon['capres'], ...$dataPaslon['cawapres']];
        foreach ($dataPaslon as $index => $data) {
            echo $index + 1 .'. '.$data->namaLengkap.' - '.$data->posisi.' Nomor Urut '.$data->nomorUrut."\n";
        }
    }
}
