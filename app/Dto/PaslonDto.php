<?php

namespace App\Dto;

use App\Enums\PosisiEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class PaslonDto extends Data
{
    public function __construct(
        public int $nomorUrut,
        public PosisiEnum $posisi,
        public string $namaLengkap,
        public string $tempatLahir,
        public Carbon $tanggalLahir,
        public int $usia,
        public array $karir,
    ) {
    }
}
