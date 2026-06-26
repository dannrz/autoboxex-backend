<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

    .header { background: #0e7490; color: #fff; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; }
    .header h1 { font-size: 18px; font-weight: bold; letter-spacing: 1px; }
    .header .meta { text-align: right; font-size: 10px; opacity: 0.85; }

    .body { padding: 20px 24px; }

    .section-title {
        font-size: 10px; font-weight: bold; text-transform: uppercase;
        letter-spacing: 1px; color: #0e7490; border-bottom: 1px solid #0e7490;
        padding-bottom: 3px; margin: 16px 0 8px;
    }

    .grid { display: table; width: 100%; border-collapse: collapse; }
    .row  { display: table-row; }
    .cell { display: table-cell; width: 25%; padding: 4px 6px; vertical-align: top; }
    .label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; }
    .value { font-size: 11px; color: #1e293b; margin-top: 1px; }

    table.insumos {
        width: 100%; border-collapse: collapse; margin-top: 6px;
    }
    table.insumos th {
        background: #0e7490; color: #fff; font-size: 9px; text-transform: uppercase;
        padding: 5px 8px; text-align: left;
    }
    table.insumos td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
    table.insumos tr:nth-child(even) td { background: #f0f9ff; }
    table.insumos tfoot td { font-weight: bold; background: #e0f2fe; }

    .obs { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px 10px; font-size: 10px; margin-top: 6px; }

    .footer { margin-top: 30px; padding: 0 24px 16px; font-size: 9px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 8px; }

    .badge {
        display: inline-block; padding: 2px 8px; border-radius: 10px;
        font-size: 9px; font-weight: bold; text-transform: uppercase;
        background: #e0f2fe; color: #0e7490;
    }

    @page { margin: 0; size: letter; }
</style>
</head>
<body>

<div class="header">
    <div>
        <h1>AUTOBOXES</h1>
        <div style="font-size:10px;opacity:.8;margin-top:2px;">Orden de Servicio</div>
    </div>
    <div class="meta">
        @if($servicio->FolioOE)
            <div style="font-size:14px;font-weight:bold;">OE #{{ $servicio->FolioOE }}</div>
        @endif
        <div>ID Movimiento: {{ $servicio->IdMovimiento }}</div>
        <div>Generado: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
</div>

<div class="body">

    {{-- CLIENTE --}}
    <div class="section-title"><i>👤</i> Cliente</div>
    <div class="grid">
        <div class="row">
            <div class="cell">
                <div class="label">Nombre</div>
                <div class="value">{{ optional($servicio->cliente)->Nombre ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">RFC</div>
                <div class="value">{{ optional($servicio->cliente)->RFC ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Sucursal</div>
                <div class="value">{{ optional($servicio->cliente)->Sucursal ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Crédito</div>
                <div class="value">{{ optional($servicio->cliente)->Credito ? optional($servicio->cliente)->Credito . ' días' : '—' }}</div>
            </div>
        </div>
        <div class="row">
            <div class="cell" style="width:75%;" colspan="3">
                <div class="label">Dirección</div>
                <div class="value">
                    {{ collect([
                        optional($servicio->cliente)->Direccion,
                        optional($servicio->cliente)->Colonia,
                        optional($servicio->cliente)->Poblacion,
                        optional($servicio->cliente)->Estado,
                        optional($servicio->cliente)->CP,
                    ])->filter()->implode(', ') ?: '—' }}
                </div>
            </div>
        </div>
    </div>

    {{-- VEHÍCULO --}}
    <div class="section-title">🚗 Vehículo</div>
    <div class="grid">
        <div class="row">
            <div class="cell">
                <div class="label">Placas</div>
                <div class="value" style="font-weight:bold;">{{ optional($servicio->vehiculo)->Placas ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Marca</div>
                <div class="value">{{ optional(optional($servicio->vehiculo)->marca)->Marca ? trim($servicio->vehiculo->marca->Marca) : '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Modelo</div>
                <div class="value">{{ optional($servicio->vehiculo)->Modelo ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Año</div>
                <div class="value">{{ optional($servicio->vehiculo)->Año ?? '—' }}</div>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <div class="label">Color</div>
                <div class="value">{{ optional($servicio->vehiculo)->Color ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Serie / VIN</div>
                <div class="value">{{ optional($servicio->vehiculo)->Serie ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Kilometraje</div>
                <div class="value">{{ $servicio->Kms ? number_format($servicio->Kms) . ' km' : '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ORDEN DE TRABAJO --}}
    <div class="section-title">📋 Orden de Trabajo</div>
    <div class="grid">
        <div class="row">
            <div class="cell">
                <div class="label">Estado</div>
                <div class="value"><span class="badge">{{ $servicio->Estado ?? 'Sin estado' }}</span></div>
            </div>
            <div class="cell">
                <div class="label">Tipo de Movimiento</div>
                <div class="value">{{ $servicio->TipMov ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Fecha de Entrada</div>
                <div class="value">{{ $servicio->FEntrada ? \Carbon\Carbon::parse($servicio->FEntrada)->format('d/m/Y') : '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Fecha de Salida</div>
                <div class="value">{{ $servicio->FSalida ? \Carbon\Carbon::parse($servicio->FSalida)->format('d/m/Y') : '—' }}</div>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <div class="label">Autoriza</div>
                <div class="value">{{ $servicio->Autoriza ?? '—' }}</div>
            </div>
            <div class="cell">
                <div class="label">Ingresa Por</div>
                <div class="value">{{ $servicio->Ingreso ?? '—' }}</div>
            </div>
        </div>
    </div>

    @if($servicio->{'Observación'})
    <div class="section-title">📝 Observaciones</div>
    <div class="obs">{{ $servicio->{'Observación'} }}</div>
    @endif

    {{-- COSTOS DE SERVICIO --}}
    @if($costos->count())
    <div class="section-title">💼 Servicios / Costos</div>
    <table class="insumos">
        <thead>
            <tr>
                <th>Servicio</th>
                <th style="text-align:right;">Cant.</th>
                <th style="text-align:right;">Precio</th>
                <th style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($costos as $c)
            <tr>
                <td>{{ $c->producto }}</td>
                <td style="text-align:right;">{{ $c->cantidad }}</td>
                <td style="text-align:right;">${{ number_format($c->precio, 2) }}</td>
                <td style="text-align:right;">${{ number_format($c->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;">Subtotal servicios:</td>
                <td style="text-align:right;">${{ number_format($costos->sum('total'), 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- INSUMOS --}}
    @if($insumos->count())
    <div class="section-title">🔧 Insumos / Refacciones</div>
    <table class="insumos">
        <thead>
            <tr>
                <th>Refacción</th>
                <th>Descripción</th>
                <th style="text-align:right;">Cant.</th>
                <th style="text-align:right;">Precio c/IVA</th>
                <th style="text-align:right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($insumos as $ins)
            <tr>
                <td>{{ optional($ins->refaccion)->Refaccion ?? $ins->IdRefaccion }}</td>
                <td>{{ optional($ins->refaccion)->Descripcion ?? '—' }}</td>
                <td style="text-align:right;">{{ $ins->Cantidad }}</td>
                <td style="text-align:right;">${{ number_format($ins->PrecioIva, 2) }}</td>
                <td style="text-align:right;">${{ number_format($ins->Cantidad * $ins->PrecioIva, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total:</td>
                <td style="text-align:right;">${{ number_format($insumos->sum(fn($i) => $i->Cantidad * $i->PrecioIva), 2) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

</div>

<div class="footer">
    AUTOBOXES &mdash; Documento generado automáticamente el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
</div>

</body>
</html>
