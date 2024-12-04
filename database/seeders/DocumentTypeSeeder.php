<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $document_types = [
            ['id' => 1, 'type_name' => 'Certificate of Incorporation'],
            ['id' => 2, 'type_name' => 'Articles  of Incorporation'],
            ['id' => 3, 'type_name' => 'List of Directors'],
            ['id' => 4, 'type_name' => 'Transfer Details'],
            ['id' => 5, 'type_name' => 'Financial Statement'],
            ['id' => 6, 'type_name' => 'Financial Statement T'],
            ['id' => 7, 'type_name' => 'Depositor Account Opening']
        ];
        foreach ($document_types as $document_type) {
            if(!DocumentType::where("type_name",$document_type["type_name"])->exists()){
                $dccmodel = new DocumentType;
                $dccmodel->id = $document_type['id'];
                $dccmodel->type_name = $document_type['type_name'];
                $dccmodel->save();
            }            
        }
    }
}
