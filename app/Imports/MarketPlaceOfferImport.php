<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class MarketPlaceOfferImport implements ToCollection
{
    private $data;
    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $this->data = $collection;
        return $collection;
    }

    public function getData()
    {
        return $this->data->except(['0']);
    }

    public function getHeader()
    {
        $headers = $this->data[0];
        return $this->data[0];
    }
}
