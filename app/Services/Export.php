<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;

class Export
{

    public static function excel(array $data, $filename = 'Items', $sheet_name = 'Sheet 1')
    {
        Excel::create($filename, function ($excel) use ($data, $sheet_name) {
            $excel->sheet($sheet_name, function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }
}