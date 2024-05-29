<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class IdsExport implements FromCollection
{
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        return new Collection(array_map(function ($id) {
            return ['id' => $id];
        }, $this->ids));
    }
}
