<?php

namespace App\Http\Controllers;

use App\Dto\PaslonDto;
use App\Enums\PosisiEnum;
use App\Services\PaslonService;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class PaslonController extends Controller
{
    private $service;

    public function __construct(PaslonService $paslonService)
    {
        $this->service = $paslonService;
    }

    public function index()
    {
        $data = $this->getData();
        if ($data) {
            return view('index', $data);
        }
    }

    public function getData()
    {
        $data = $this->fetchDataFromAPI();
        if ($data) {
            $capres = $this->getPaslonData($data['calon_presiden'], PosisiEnum::PRESIDEN());
            $cawapres = $this->getPaslonData($data['calon_wakil_presiden'], PosisiEnum::WAKIL_PRESIDEN());

            usort($capres, function ($a, $b) {
                return $a->nomorUrut - $b->nomorUrut;
            });

            usort($cawapres, function ($a, $b) {
                return $a->nomorUrut - $b->nomorUrut;
            });

            return compact('capres', 'cawapres');
        }
    }

    public function getPaslonData(?array $paslonData, PosisiEnum $posisi): ?array
    {
        if (! $paslonData) {
            return null;
        }
        $paslonArray = [];

        foreach ($paslonData as $capres) {
            $tempatLahir = $this->service->getTempatLahir($capres['tempat_tanggal_lahir']);
            $tanggalLahir = $this->service->parseTanggalLahir($capres['tempat_tanggal_lahir']);
            $umur = $this->service->hitungUmur($tanggalLahir);
            $karir = $this->service->parseKarir($capres['karir']);

            $paslonArray[] = new PaslonDto(
                $capres['nomor_urut'],
                $posisi,
                $capres['nama_lengkap'],
                $tempatLahir,
                Carbon::createFromFormat('d-m-Y', $tanggalLahir),
                $umur,
                $karir,
            );
        }

        return $paslonArray;
    }

    protected function fetchDataFromAPI(): ?array
    {
        try {
            $response = Http::get('https://mocki.io/v1/92a1f2ef-bef2-4f84-8f06-1965f0fca1a7');
            if ($response->successful()) {
                return $response->json();
            }
        } catch (ConnectionException $e) {
            report($e->getMessage());
        }

        return null;
    }
}
