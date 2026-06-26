<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

    /* HEADER */
    .header { background: #0f172a; color: #fff; padding: 16px 24px; }
    .header-inner { display: table; width: 100%; }
    .header-left  { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; vertical-align: middle; text-align: right; }
    .brand  { font-size: 20px; font-weight: bold; letter-spacing: 2px; color: #38bdf8; }
    .doc-title { font-size: 11px; color: #94a3b8; margin-top: 2px; }
    .folio-badge { background: #38bdf8; color: #0f172a; font-weight: bold; font-size: 15px;
        padding: 4px 14px; border-radius: 4px; display: inline-block; }
    .meta-small { font-size: 9px; color: #94a3b8; margin-top: 4px; }

    .body { padding: 18px 24px; }

    /* SECCIONES */
    .section-title {
        font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;
        color: #0ea5e9; border-bottom: 1px solid #0ea5e9; padding-bottom: 3px; margin: 14px 0 7px;
    }

    /* GRID DE DATOS */
    .info-grid { display: table; width: 100%; }
    .info-row  { display: table-row; }
    .info-cell { display: table-cell; padding: 3px 6px 3px 0; vertical-align: top; width: 25%; }
    .lbl { font-size: 8px; text-transform: uppercase; color: #64748b; font-weight: bold; }
    .val { font-size: 11px; color: #1e293b; margin-top: 1px; }
    .val-bold { font-weight: bold; }

    /* TABLAS */
    table.items { width: 100%; border-collapse: collapse; margin-top: 4px; }
    table.items th {
        background: #0f172a; color: #fff; font-size: 9px; text-transform: uppercase;
        padding: 5px 8px; text-align: left;
    }
    table.items td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
    table.items tr:nth-child(even) td { background: #f8fafc; }
    table.items .num { text-align: right; }
    table.items tfoot td {
        font-weight: bold; background: #f0f9ff; border-top: 2px solid #0ea5e9;
        font-size: 11px;
    }

    /* TOTALES */
    .totals-box {
        margin-top: 10px; border: 1px solid #e2e8f0; border-radius: 4px;
        display: table; width: 100%;
    }
    .total-row { display: table-row; }
    .total-label { display: table-cell; padding: 5px 12px; font-size: 10px; color: #64748b; width: 70%; border-bottom: 1px solid #f1f5f9; }
    .total-value { display: table-cell; padding: 5px 12px; font-size: 10px; text-align: right; border-bottom: 1px solid #f1f5f9; }
    .total-grand .total-label { font-weight: bold; font-size: 12px; color: #0f172a; border-bottom: none; }
    .total-grand .total-value { font-weight: bold; font-size: 13px; color: #0ea5e9; border-bottom: none; }

    .obs-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;
        padding: 7px 10px; font-size: 10px; margin-top: 5px; }

    .firma-area { margin-top: 36px; display: table; width: 100%; }
    .firma-cell { display: table-cell; width: 33%; text-align: center; padding: 0 12px; }
    .firma-line { border-top: 1px solid #94a3b8; padding-top: 4px; font-size: 9px; color: #64748b; }

    .footer { margin-top: 20px; padding: 8px 24px; font-size: 8px; color: #94a3b8;
        text-align: center; border-top: 1px solid #e2e8f0; }

    @page { margin: 0; size: letter; }
</style>
</head>
<body>

<div class="header">
    <div class="header-inner">
        <div class="header-left">
            <div class="brand">AUTOBOXES</div>
            <div class="doc-title">Presupuesto de Servicios</div>
        </div>
        <div class="header-right">
            @if($servicio->FolioOE)
                <div class="folio-badge">OE # {{ $servicio->FolioOE }}</div>
            @endif
            <div class="meta-small">ID Movimiento: {{ $servicio->IdMovimiento }}</div>
            <div class="meta-small">Fecha: {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>
</div>

<div class="body">

    {{-- CLIENTE --}}
    <div class="section-title">Cliente</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell">
                <div class="lbl">Nombre</div>
                <div class="val val-bold">{{ optional($servicio->cliente)->Nombre ?? '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">RFC</div>
                <div class="val">{{ optional($servicio->cliente)->RFC ?? '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Sucursal</div>
                <div class="val">{{ optional($servicio->cliente)->Sucursal ?? '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Crédito</div>
                <div class="val">{{ optional($servicio->cliente)->Credito ? optional($servicio->cliente)->Credito.' días' : '—' }}</div>
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell" style="width:75%">
                <div class="lbl">Dirección</div>
                <div class="val">
                    {{ collect([optional($servicio->cliente)->Direccion, optional($servicio->cliente)->Colonia,
                        optional($servicio->cliente)->Poblacion, optional($servicio->cliente)->Estado,
                        optional($servicio->cliente)->CP])->filter()->implode(', ') ?: '—' }}
                </div>
            </div>
            <div class="info-cell">
                <div class="lbl">Teléfono</div>
                <div class="val">{{ optional($servicio->cliente)->TelMovil ?? optional($servicio->cliente)->TelOficina ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- VEHÍCULO --}}
    <div class="section-title">Vehículo</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-cell">
                <div class="lbl">Placas</div>
                <div class="val val-bold" style="font-size:13px">{{ optional($servicio->vehiculo)->Placas ?? '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Marca</div>
                <div class="val">{{ isset($servicio->vehiculo->marca) ? trim($servicio->vehiculo->marca->Marca) : '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Modelo</div>
                <div class="val">{{ optional($servicio->vehiculo)->Modelo ?? '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Año</div>
                <div class="val">{{ optional($servicio->vehiculo)->Año ?? '—' }}</div>
            </div>
        </div>
        <div class="info-row">
            <div class="info-cell">
                <div class="lbl">Color</div>
                <div class="val">{{ optional($servicio->vehiculo)->Color ?? '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Serie / VIN</div>
                <div class="val">{{ optional($servicio->vehiculo)->Serie ?? '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Kilometraje</div>
                <div class="val">{{ $servicio->Kms ? number_format($servicio->Kms).' km' : '—' }}</div>
            </div>
            <div class="info-cell">
                <div class="lbl">Fecha entrada</div>
                <div class="val">{{ $servicio->FEntrada ? \Carbon\Carbon::parse($servicio->FEntrada)->format('d/m/Y') : '—' }}</div>
            </div>
        </div>
    </div>

    {{-- INSUMOS / REFACCIONES --}}
    @if($insumos->count())
    <div class="section-title">Refacciones e Insumos</div>
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Refacción / Insumo</th>
                <th style="text-align:right">Cant.</th>
                <th style="text-align:right">Precio Unit.</th>
                <th style="text-align:right">IVA inc.</th>
                <th style="text-align:right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($insumos as $i => $ins)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ optional($ins->refaccion)->{'Refacción'} ?? optional($ins->refaccion)->Refaccion ?? 'ID '.$ins->IdRefaccion }}</td>
                <td class="num">{{ number_format($ins->Cantidad, 2) }}</td>
                <td class="num">${{ number_format($ins->Precio, 2) }}</td>
                <td class="num">${{ number_format($ins->PrecioIva, 2) }}</td>
                <td class="num">${{ number_format($ins->Cantidad * $ins->PrecioIva, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right">Subtotal refacciones:</td>
                <td class="num">${{ number_format($insumos->sum(fn($i) => $i->Cantidad * $i->PrecioIva), 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- SERVICIOS / COSTOS --}}
    @if($costos->count())
    <div class="section-title">Servicios</div>
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th style="text-align:right">Cant.</th>
                <th style="text-align:right">Precio</th>
                <th style="text-align:right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($costos as $i => $costo)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $costo->producto }}</td>
                <td class="num">{{ $costo->cantidad ?? 1 }}</td>
                <td class="num">${{ number_format($costo->precio, 2) }}</td>
                <td class="num">${{ number_format(($costo->cantidad ?? 1) * $costo->precio, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right">Subtotal servicios:</td>
                <td class="num">${{ number_format($costos->sum(fn($c) => ($c->cantidad ?? 1) * $c->precio), 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- TOTALES --}}
    @php
        $totalInsumos = $insumos->sum(fn($i) => $i->Cantidad * $i->PrecioIva);
        $totalCostos  = $costos->sum(fn($c) => ($c->cantidad ?? 1) * $c->precio);
        $gran_total   = $totalInsumos + $totalCostos;
    @endphp
    <div style="width:45%;margin-left:55%;margin-top:10px;">
        <div class="totals-box">
            @if($insumos->count())
            <div class="total-row">
                <div class="total-label">Subtotal refacciones</div>
                <div class="total-value">${{ number_format($totalInsumos, 2) }}</div>
            </div>
            @endif
            @if($costos->count())
            <div class="total-row">
                <div class="total-label">Subtotal servicios</div>
                <div class="total-value">${{ number_format($totalCostos, 2) }}</div>
            </div>
            @endif
            <div class="total-row total-grand">
                <div class="total-label">TOTAL</div>
                <div class="total-value">${{ number_format($gran_total, 2) }}</div>
            </div>
        </div>
    </div>

    @if($servicio->{'Observación'})
    <div class="section-title" style="margin-top:14px">Observaciones</div>
    <div class="obs-box">{{ $servicio->{'Observación'} }}</div>
    @endif

    {{-- FIRMAS --}}
    <div class="firma-area" style="margin-top:40px">
        <div class="firma-cell">
            <div class="firma-line">Autoriza: {{ $servicio->Autoriza ?? '________________________' }}</div>
        </div>
        <div class="firma-cell">
            <div class="firma-line">Recibe conforme</div>
        </div>
        <div class="firma-cell">
            <div class="firma-line">Entrega</div>
        </div>
    </div>

</div>

<div class="footer">
    AUTOBOXES &mdash; Presupuesto generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }} &mdash; Documento no fiscal
</div>

</body>
</html>
