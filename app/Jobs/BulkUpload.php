<?php

namespace App\Jobs;

use Throwable;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BulkUpload implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $header;
    public $table;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $header,$table)
    {
        $this->data   = $data;
        $this->header = $header;
        $this->table  = $table;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        foreach ($this->data as $data) {
            $bulkData = array_combine($this->header, $data);
            if(isset($bulkData['password']))
                $bulkData['password'] = Hash::make($bulkData['password']);
            DB::table($this->table)->insert($bulkData);
        }
    }
}
