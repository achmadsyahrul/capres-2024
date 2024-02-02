<?php

use App\Console\Commands\CapreskuCommand;
use App\Http\Controllers\PaslonController;
use App\Services\PaslonService;

it('displays the profile of candidate', function () {
    $paslonControllerMock = mock(PaslonController::class);

    $paslonServiceMock = mock(PaslonService::class);

    $command = new CapreskuCommand($paslonControllerMock, $paslonServiceMock);

    ob_start();
    $command->handle();
    $output = ob_get_clean();

    $expectedOutput = '1. Anies Rasyid Baswedan - PRESIDEN Nomor Urut 1';
    expect($output)->toContain($expectedOutput);
});
