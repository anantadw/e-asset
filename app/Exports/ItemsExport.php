<?php

namespace App\Exports;

use App\Models\Item;
// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ItemsExport implements FromQuery, WithMapping, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    use Exportable;

    public function query()
    {
        return Item::query();
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getStyle('A:F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:F')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F' . $sheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6.43,
            'B' => 20.71,
            'C' => 20.71,
            'D' => 10.71,
            'E' => 20.71,
            'F' => 35
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'F' => '[$-id-ID]dddd, dd mmmm yyyy - hh.mm.ss',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Kategori',
            'Sisa Stok',
            'Didaftarkan Oleh',
            'Waktu'
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->category->name,
            $item->stock,
            $item->user->name,
            Date::dateTimeToExcel($item->created_at),
        ];
    }

    // public function view(): View
    // {
    //     return view('admin.export-excel', [
    //         'items' => Item::all(),
    //     ]);
    // }
}
