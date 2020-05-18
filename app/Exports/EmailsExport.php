<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class EmailsExport implements FromCollection
{
    public $emails;

    public function __construct($emails)
    {
        $this->emails = $emails;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->emails;
    }
}
