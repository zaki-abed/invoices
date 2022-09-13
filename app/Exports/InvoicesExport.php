<?php

namespace App\Exports;

use App\Invioce;
use Maatwebsite\Excel\Concerns\FromCollection;


class InvoicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Invioce::all();
        return Invioce::select('invoice_number', 'product', 'total')->get();
    }
}
