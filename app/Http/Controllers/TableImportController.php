<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use DB;
use Validator;

ini_set('auto_detect_line_endings', 1);

class TableImportController extends Controller
{
    protected $table_name;
    protected $columns;
    protected $rows;

    public function upload()
    {

        return view('upld_form');

    }

    public function run() 
    {
        $this->validate(request(), [
            'table_name' => 'string|alpha_dash',
            'import_csv' => 'required',
            'import_csv.mimeType' => 'mimetypes:text/csv,text/plain'
            ]);

        // validate mime in some hacky way
        // can't figure out why it isn't working above...
        if (request()->file('import_csv')->getMimeType() != 'text/csv'
            && request()->file('import_csv')->getMimeType() != 'text/plain') {
            echo 'This is a not a <code>.csv</code> file';exit();
            //header( "refresh:5;url=/import-table" );
        }

        $this->parseCsv();

        $this->createTable();

        $rows = $this->insertValues();
        $sliced = array_slice($rows, 0, 10);

        return response()->view('success', compact('rows', 'sliced'));
    }

        private function parseCsv() 
        {

            $handle = fopen(request()->file('import_csv'), 'r');

            while ( ($row = fgetcsv($handle)) !== false) {
                $this->rows[] = $row;
            }

            $this->columns = array_shift($this->rows);

            foreach ($this->rows as $i=>$row) {
                $this->rows[$i] = array_combine($this->columns, $row);
            }

            fclose($handle);
        }

        private function createTable() 
        {
            // part of me thinks you could probably call make:migration here
                // but making database/migrations writable seems very wrong...
            $this->table_name = request()->table_name;
            if (!$this->table_name) {
                $name = explode('.', request()->file('import_csv')->getClientOriginalName());
                $this->table_name = $name[0] . time();
            }

            Schema::create($this->table_name, function (Blueprint $table) {

                // at least check for IDs i may have assigned and dowloaded...
                $id = in_array('id', $this->columns);
                if (!$id) {
                    $table->increments('id');
                }

                foreach ($this->columns as $column) {
                    $table->string($column);
                }
            });
        }

        private function insertValues() 
        {
            foreach (array_chunk($this->rows,1000) as $r) {
                DB::table($this->table_name)->insert($r);
            }
            return $this->rows;
        }
}
