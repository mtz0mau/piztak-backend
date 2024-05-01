<!doctype html>
<html lang="es">
<head>
  <title>Pedidos</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body class="row">
  <ul class="list-group mb-5 col-3">
    <li class="list-group-item active py-0" style="font-size: .95rem" aria-current="true">Nov 22-27 2022</li>
    <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Total de pedidos: <span class="fw-bold">{{ $primerPedido['total'] }}</span></li>
    <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Suma de pedidos: ${{ $primerPedido['suma'] }}</li>
    <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Costo total de pedidos: ${{ $primerPedido['costo'] }}</li>
    <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Diferencia: ${{ $primerPedido['diferencia'] }}</li>
    <li class="list-group-item py-0 fw-normal bg-danger text-white fw-bold" style="font-size: .95rem">Pedidos faltantes: {{ $primerPedido['pedidosFaltantes'] }}</li>
  </ul>


  @php
    $count = 1;
  @endphp
  @foreach ($orders as $i => $pedidos)
    <ul class="list-group mb-5 col-3">
      <li class="list-group-item active py-0" style="font-size: .95rem" aria-current="true">{{ $count }}° Semana</li>
      <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Total de pedidos: <span class="fw-bold">{{ count($pedidos) }}</span></li>
      @php
        $costo = count($pedidos) * 100;
        $suma = 0;
        foreach ($pedidos as $pedido) $suma+=floatval($pedido->total);
        $diferencia = $suma - $costo; 

        $pedidosFaltantes = ceil((4800 - $diferencia) / 100);
      @endphp
      <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Suma de pedidos: ${{ $suma }}</li>
      <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Costo total de pedidos: ${{ $costo }}</li>
      <li class="list-group-item py-0 fw-normal" style="font-size: .95rem">Diferencia: ${{ $diferencia }}</li>
      <li class="list-group-item py-0 fw-normal bg-danger text-white fw-bold" style="font-size: .95rem">Pedidos faltantes: {{ $pedidosFaltantes }}</li>
    </ul>
    @php
      $count++;
    @endphp
  @endforeach

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>