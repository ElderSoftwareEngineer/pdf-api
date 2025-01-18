<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // Alias do barryvdh/laravel-dompdf (ver config/app.php)
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function gerarPdf(Request $request)
    {
        // 1. Receber dados em JSON
        //    Supondo que o JSON tenha campos como 'nome', 'conteudo', etc.
        $dados = $request->all();
        // dd($dados);
        // 2. Gerar o PDF a partir de uma view
        //    Crie uma view Blade chamada 'meu-pdf.blade.php' em resources/views
        //    e use as variáveis passadas pelo compact ou array associativo
        $pdf = PDF::loadView('meu-pdf', ['dados' => $dados]);

        // 3. Renderizar o PDF em formato binário
        $conteudoPdf = $pdf->output();

        // 4. Criar um nome único para o arquivo, por ex:
        $nomeArquivo = 'documento-' . time() . '.pdf';

        // 5. Salvar no S3
        Storage::disk('s3')->put($nomeArquivo, $conteudoPdf);

        // 6. Obter a URL pública ou de download (dependendo das configs do Bucket)
        //    Se o bucket for público e as ACLs estiverem configuradas corretamente,
        //    podemos usar a função url() ou temporaryUrl().
        //    Exemplo de URL pública:
        $url = Storage::disk('s3')->url($nomeArquivo);

        // Retornar a URL para download
        return response()->json([
            'success' => true,
            'message' => 'PDF gerado com sucesso.',
            'pdf_url' => $url
        ]);
    }
}
