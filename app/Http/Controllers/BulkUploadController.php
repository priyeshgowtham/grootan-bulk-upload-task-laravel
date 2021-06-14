<?php

namespace App\Http\Controllers;

use App\Jobs\BulkUpload;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class BulkUploadController extends Controller
{

    public function upload(){
        if(request()->has('mycsv'))
        {
            $data=file(request()->mycsv);
            $chunks = array_chunk($data, 1000);
            $header = [];
            date_default_timezone_set('Asia/Kolkata'); 
            $table_name=date("Y_m_d_H_i_s");
            $fields=array_map('str_getcsv', $data)[0];
            $batch  = Bus::batch([])->dispatch();
            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) {
                $table->increments('id');
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        $table->string($field);
                    }
                }
                $table->timestamps();
            });
            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);

                if ($key === 0) {
                    $header = $data[0];
                    unset($data[0]);
                }

                $batch->add(new BulkUpload($data, $header,$table_name));
            }
            return $batch;

        }
        // return response()->json(request()->has('mycsv'));
    }
    public function batch()
    {
        $batchId = request('id');
        return Bus::findBatch($batchId);
    }

    
}
