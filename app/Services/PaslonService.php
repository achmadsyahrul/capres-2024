<?php

namespace App\Services;

use App\Dto\KarirDto;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaslonService
{
    public function parseTanggalLahir(string $tempatTanggalLahir): string
    {
        if ($this->isValidTempatTanggalLahir($tempatTanggalLahir)) {
            $monthMap = [
                'Januari' => '01',
                'Februari' => '02',
                'Maret' => '03',
                'April' => '04',
                'Mei' => '05',
                'Juni' => '06',
                'Juli' => '07',
                'Agustus' => '08',
                'September' => '09',
                'Oktober' => '10',
                'November' => '11',
                'Desember' => '12',
            ];
            $parts = explode(', ', $tempatTanggalLahir);
            $tanggalBulanTahun = explode(' ', $parts[1]);
            $tanggal = $tanggalBulanTahun[0];
            $bulan = $monthMap[$tanggalBulanTahun[1]];
            $tahun = $tanggalBulanTahun[2];

            return $tanggal.'-'.$bulan.'-'.$tahun;
        }

        return 'Tempat tanggal lahir tidak valid';
    }

    public function getTempatLahir(string $tempatTanggalLahir): string
    {
        if ($this->isValidTempatTanggalLahir($tempatTanggalLahir)) {
            $parts = explode(', ', $tempatTanggalLahir);

            return $parts[0];
        }

        return 'Tempat tanggal lahir tidak valid';
    }

    protected function isValidTempatTanggalLahir(string $tempatTanggalLahir): bool
    {
        $parts = explode(', ', $tempatTanggalLahir);

        return count($parts) === 2;
    }

    public function hitungUmur(string $tanggalLahir): int
    {
        $tgl_lahir = Carbon::createFromFormat('d-m-Y', $tanggalLahir);

        return $tgl_lahir->diffInYears(Carbon::now());
    }

    public function parseKarir(array $karirData): array
    {
        $karirArray = [];
        foreach ($karirData as $karir) {
            $parsedKarir = $this->parseSingleKarir($karir);
            if ($parsedKarir !== null) {
                $karirArray[] = $parsedKarir;
            }
        }

        return $karirArray;
    }

    private function parseSingleKarir(string $karir): KarirDto
    {
        $jabatan = Str::of($karir)->beforeLast('(');
        $tahunMulai = Str::of($karir)->afterLast('(')->beforeLast('-')->toInteger();

        if (Str::of($karir)->contains('-')) {
            $tahunSelesai = Str::of($karir)->after('-')->beforeLast(')')->toInteger();
        } else {
            $tahunSelesai = $tahunMulai;
        }

        return new KarirDto($jabatan, $tahunMulai, $tahunSelesai);
    }
}
