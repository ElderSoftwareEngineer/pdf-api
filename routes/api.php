<?php

use App\Http\Controllers\PdfController;

Route::post('/gerar-pdf', [PdfController::class, 'gerarPdf']);
