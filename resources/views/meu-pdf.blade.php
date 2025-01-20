<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Exemplo DOMPDF com float</title>
  <style>
    .col-container {
      /* Um "wrapper" que vai agrupar as colunas */
      width: 100%;
      margin: 0 auto;
    }

    .col0 {
      /* Configura cada coluna para flutuar à esquerda */
      float: left;
      width: 70%;      /* Ajuste conforme necessário */
      margin-right: 2%; /* Espaçamento entre colunas */
      box-sizing: border-box;
      /* background-color: #f0f0f0; */
      padding: 10px;
    }
    
    .col {
      /* Configura cada coluna para flutuar à esquerda */
      float: left;
      width: 26%;      /* Ajuste conforme necessário */
      margin-right: 2%; /* Espaçamento entre colunas */
      box-sizing: border-box;
      /* background-color: #f0f0f0; */
      padding: 5px;
    }
    
    /* Última coluna: retira a margem da direita, para caber direitinho */
    .col:last-child {
      margin-right: 0;
    }

    /* "Limpa" o float para que elementos abaixo não fiquem flutuando */
    .clearfix::after {
      content: "";
      display: block;
      clear: both;
    }

    .info{
        font-size: 16px;
        margin-top: 0px;
        padding: 0px 0px 0px 0px;
    }
    hr.full-width-line {
      width: 100%;                    /* ocupa largura total */
      border: none;                   /* remove a borda padrão do hr */
      border-bottom: 1px solid #ccc;  /* linha bem clara */
      margin: 0;                      /* remove margens (top e bottom) */
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border: none;
    }

    th {
      background-color: #f9f9f9;
    }

    .total {
      font-weight: bold;
    }

    .right-align {
      text-align: right;
    }

  </style>
</head>
<body>
  <div class="col-container clearfix">
  <div style="text-align:center;margin:0 0 0 0">Número de Registro: 22798</div>
  <hr class="full-width-line">
  <br>
  <br>
    <div class="col0 info">
      Paciente: {{$dados["name"]}} <br>
      Sexo: {{$dados["sexo"]}} <br>
      Convênio: {{$dados["convenio"]}} <br>
      DT do Pedido: 30/12/2024 <br>
      Número telefone: {{$dados["phone"]}}
    </div>
    <!-- <div class="col info"> -->
      <img class="col info" src="https://armazenamento-pdf-api.s3.us-east-1.amazonaws.com/logo_anacli.png" alt="Logo" style="max-height: 150px;">
    <!-- </div> -->
  </div>
    @php
      $itemsPerPage = 13; // Limite de itens por página
      $pages = array_chunk($dados['content'], $itemsPerPage); // Divide os dados em páginas
      $totalPages = count($pages); // Total de páginas
      $totalGeral = array_sum(array_column($dados['content'], 'total_value')); // Total de todas as páginas
    @endphp

    @foreach ($pages as $pageIndex => $page)
      <table>
          <thead>
              <tr>
                  <th>Código</th>
                  <th>Descrição</th>
                  <th>Qtde</th>
                  <th class="right-align">Valor Unitário</th>
                  <th class="right-align">Valor Total</th>
              </tr>
          </thead>
          <tbody>
              @php
                  $subtotal = 0; // Inicializa o subtotal da página
              @endphp

              @foreach ($page as $item)
                  <tr>
                      <td>{{ $item['cod'] }}</td>
                      <td>{{ $item['description'] }}</td>
                      <td>{{ $item['qtde'] }}</td>
                      <td class="right-align">{{ number_format($item['unit_value'], 2, ',', '.') }}</td>
                      <td class="right-align">{{ number_format($item['total_value'], 2, ',', '.') }}</td>
                  </tr>
                  @php
                      $subtotal += $item['total_value']; // Incrementa o subtotal
                  @endphp
              @endforeach
          </tbody>
          <tfoot>
              <tr>
                  <td colspan="4" class="right-align total">Subtotal:</td>
                  <td class="right-align total">{{ number_format($subtotal, 2, ',', '.') }}</td>
              </tr>
              @if ($totalPages === 1)
                <tr>
                    <td colspan="4" class="right-align total">Total Geral:</td>
                    <td class="right-align total">{{ number_format($totalGeral, 2, ',', '.') }}</td>
                </tr>
              @endif
          </tfoot>
      </table>

      <div style="text-align: center; margin-top: 20px; font-size: 14px;">
          Página {{ $pageIndex + 1 }} de {{ $totalPages }}
      </div>

      @if ($pageIndex + 1 < $totalPages)
          <div style="page-break-after: always;"></div>
      @endif
    @endforeach

    @if ($totalPages > 1)
        <div style="text-align: right; margin-top: 20px; font-size: 16px; font-weight: bold;">
            Total Geral: {{ number_format($totalGeral, 2, ',', '.') }}
        </div>
    @endif

</body>
</html>
