<?php

use App\Enums\PosisiEnum;
use App\Http\Controllers\PaslonController;
use App\Services\PaslonService;
use Illuminate\Support\Facades\Http;

it('can parse tanggal lahir correctly', function () {
    $service = new PaslonService();
    $tempatTanggalLahir = 'Jakarta, 02 Februari 1990';
    $expectedResult = '02-02-1990';

    expect($service->parseTanggalLahir($tempatTanggalLahir))->toBe($expectedResult);
});

it('can get tempat lahir correctly', function () {
    $service = new PaslonService();
    $tempatTanggalLahir = 'Jakarta, 02 Februari 1990';
    $expectedResult = 'Jakarta';

    expect($service->getTempatLahir($tempatTanggalLahir))->toBe($expectedResult);
});

it('returns error message for invalid tempat tanggal lahir', function () {
    $service = new PaslonService();
    $invalidTempatTanggalLahir = 'Invalid Tempat Tanggal Lahir';

    expect($service->parseTanggalLahir($invalidTempatTanggalLahir))->toBe('Tempat tanggal lahir tidak valid');
    expect($service->getTempatLahir($invalidTempatTanggalLahir))->toBe('Tempat tanggal lahir tidak valid');
});

it('returns null if paslonData is null', function () {
    $paslon = new PaslonController(new PaslonService());
    $posisi = PosisiEnum::PRESIDEN();

    $result = $paslon->getPaslonData(null, $posisi);

    expect($result)->toBeNull();
});

it('returns null if fetching data from API fails', function () {
    Http::fake([
        'https://mocki.io/v1/92a1f2ef-bef2-4f84-8f06-1965f0fca1a7' => Http::response([], 500),
    ]);

    $paslon = new class(new PaslonService()) extends PaslonController
    {
        public function fetchDataFromAPIForTesting(): ?array
        {
            return $this->fetchDataFromAPI();
        }
    };

    $result = $paslon->fetchDataFromAPIForTesting();
    expect($result)->toBeNull();
});
