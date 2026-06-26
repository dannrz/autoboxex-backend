<?php

namespace App\Exports;

use App\Models\{Cliente, Service};
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithStyles,
    WithTitle,
    WithColumnWidths,
    ShouldAutoSize,
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{Alignment, Border, Fill};
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ServiciosExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        private ?int $folioOE = null,
        private ?int $idCliente = null,
    ) {}

    public function collection(): Collection
    {
        $query = Service::with(['cliente', 'vehiculo.marca'])
            ->orderBy('FolioOE', 'desc')
            ->orderBy('IdMovimiento', 'desc');

        if ($this->folioOE) {
            $query->where('FolioOE', $this->folioOE);
        }

        if ($this->idCliente) {
            $query->where('IdCliente', $this->idCliente);
        }

        return $query->get()->map(fn($s) => [
            $s->IdMovimiento,
            $s->FolioOE,
            optional($s->cliente)->Nombre,
            optional($s->cliente)->RFC,
            optional($s->vehiculo)->Placas,
            optional(optional($s->vehiculo)->marca)->Marca ? Str::trim($s->vehiculo->marca->Marca) : null,
            optional($s->vehiculo)->Modelo,
            optional($s->vehiculo)->Año,
            optional($s->vehiculo)->Color,
            optional($s->vehiculo)->Serie,
            $s->Kms,
            $s->TipMov,
            $s->Estado,
            $s->FEntrada ? \Carbon\Carbon::parse($s->FEntrada)->format('d/m/Y') : null,
            $s->FSalida  ? \Carbon\Carbon::parse($s->FSalida)->format('d/m/Y')  : null,
            $s->Autoriza,
            $s->Ingreso,
            $s->{'Observación'},
        ]);
    }

    public function headings(): array
    {
        return [
            'ID Movimiento',
            'Folio OE',
            'Cliente',
            'RFC',
            'Placas',
            'Marca',
            'Modelo',
            'Año',
            'Color',
            'Serie',
            'Kilometraje',
            'Tipo Movimiento',
            'Estado',
            'Fecha Entrada',
            'Fecha Salida',
            'Autoriza',
            'Ingresa Por',
            'Observaciones',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0E7490']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Servicios';
    }
}
