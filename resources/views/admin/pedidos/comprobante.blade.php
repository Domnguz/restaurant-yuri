<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ strtoupper($pedido->comprobante_tipo ?: 'boleta') }} interna · Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 28px; background: #f5f2ed; color: #202020; font: 14px/1.5 Arial, sans-serif; }
        .receipt { width: min(100%, 720px); margin: 0 auto; padding: 38px; background: white; border: 1px solid #e5dfd6; }
        .top { display:flex; justify-content:space-between; gap:20px; border-bottom:2px solid #c85120; padding-bottom:20px; }
        h1 { margin:0; font-size:26px; } h2 { margin:0; font-size:18px; text-align:right; }
        p { margin:4px 0; } .muted { color:#6b625a; font-size:12px; }
        .notice { margin:18px 0; padding:10px 12px; background:#fff5e7; color:#754519; font-size:12px; }
        .info { display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:14px 0; border-bottom:1px solid #e8e3dc; }
        .label { color:#777; font-size:10px; letter-spacing:.08em; text-transform:uppercase; }
        table { width:100%; border-collapse:collapse; margin:18px 0; }
        th { text-align:left; color:#777; font-size:10px; letter-spacing:.06em; }
        th,td { padding:10px 6px; border-bottom:1px solid #eee9e2; vertical-align:top; }
        .right { text-align:right; white-space:nowrap; } .total { display:flex; justify-content:space-between; padding-top:14px; font-size:20px; font-weight:bold; }
        .actions { width:min(100%,720px); margin:16px auto; display:flex; justify-content:flex-end; gap:10px; }
        button,a { border:0; border-radius:8px; padding:10px 16px; background:#c85120; color:white; text-decoration:none; font:inherit; cursor:pointer; }
        a { background:white; color:#553d2d; border:1px solid #ddd4ca; }
        footer { margin-top:30px; padding-top:14px; border-top:1px solid #e8e3dc; text-align:center; }
        @media print { body { padding:0; background:#fff; } .receipt { width:100%; border:0; padding:0; } .actions { display:none; } @page { margin:14mm; } }
        @media(max-width:520px) { body { padding:12px; } .receipt { padding:20px; } .top { display:block; } h2 { text-align:left; margin-top:12px; } }
    </style>
</head>
<body>
    @php($tipo = $pedido->comprobante_tipo ?: 'boleta')
    <div class="receipt">
        <div class="top">
            <div><h1>Mr. Yuri</h1><p class="muted">Restaurante &amp; tradición</p></div>
            <div><h2>{{ $tipo === 'factura' ? 'FACTURA' : 'BOLETA' }} · INTERNA</h2><p class="muted">Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</p></div>
        </div>
        <div class="notice">Formato interno para impresión. No constituye un comprobante de pago electrónico autorizado por SUNAT.</div>
        <div class="info">
            <div><div class="label">Cliente</div><strong>{{ $pedido->cliente_nombre ?: 'Cliente' }}</strong></div>
            <div><div class="label">{{ $tipo === 'factura' ? 'RUC' : 'Documento' }}</div><strong>{{ $pedido->cliente_documento ?: '—' }}</strong></div>
            @if($tipo === 'factura')<div><div class="label">Dirección fiscal</div><strong>{{ $pedido->cliente_direccion ?: '—' }}</strong></div>@endif
            <div><div class="label">Fecha y hora</div><strong>{{ ($pedido->pagado_at ?: $pedido->created_at)->format('d/m/Y H:i') }}</strong></div>
            <div><div class="label">Mesa</div><strong>{{ $pedido->mesa->numero }} · {{ ucfirst($pedido->mesa->zona) }}</strong></div>
            <div><div class="label">Atendido por</div><strong>{{ $pedido->usuario->name }}</strong></div>
        </div>
        <table>
            <thead><tr><th>DESCRIPCIÓN</th><th class="right">IMPORTE</th></tr></thead>
            <tbody>
                @foreach($pedido->detalles as $detalle)
                    <tr><td><strong>{{ $detalle->productos->pluck('nombre')->join(' + ') ?: 'Producto' }}</strong>@if($detalle->componentesMixto->isNotEmpty())<br><span class="muted">{{ $detalle->componentesMixto->pluck('nombre')->join(', ') }}</span>@endif @if($detalle->observaciones)<br><span class="muted">Nota: {{ $detalle->observaciones }}</span>@endif</td><td class="right">S/ {{ number_format((float)$detalle->precio, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="info"><div><div class="label">Método de pago</div><strong>{{ ucfirst($pedido->metodo_pago ?: '—') }}</strong></div><div><div class="label">Pedido</div><strong>#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</strong></div></div>
        <div class="total"><span>Total</span><span>S/ {{ number_format((float)$pedido->total, 2) }}</span></div>
        <footer class="muted">Gracias por tu visita. ¡Esperamos atenderte pronto!</footer>
    </div>
    <div class="actions"><a href="{{ route('admin.pedidos.show', $pedido) }}">Volver al pedido</a><button type="button" onclick="window.print()">Imprimir comprobante</button></div>
</body>
</html>
