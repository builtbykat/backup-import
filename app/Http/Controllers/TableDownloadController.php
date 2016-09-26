<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Schema;

use DB;

class TableDownloadController extends Controller
{
    public function show() {
        if (DB::connection()->getDatabaseName()) {
            $tables = DB::select('SHOW TABLES');
            return view('bkup_form', compact('tables'));
        }
    }

    public function backup() {

        $this->validate(request(), [
            'table_chosen' => 'required'
            ]);

        $columns = $this->queryColumns();
        $rows = $this->queryRows();

        $filename = $this->compileCsv($columns, $rows);

        $headers = array('Content-Type' => 'text/csv');
        return response()->download($filename, $filename, $headers)->deleteFileAfterSend(true);
    }

        private function queryColumns()
        {
            $columns = Schema::getColumnListing(request()->table_chosen);
            return $columns;
        }

        private function queryRows() {
            DB::setFetchMode(\PDO::FETCH_ASSOC);
            $rows = DB::table(request()->table_chosen)->get();
            DB::setFetchMode(\PDO::FETCH_CLASS);

            return $rows;
        }

        private function compileCsv($columns, $rows)
        {
            $filename = request()->table_chosen.'.csv';
            
            $handle = fopen($filename, 'w+');
            
            fputcsv($handle, $columns);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);

            return $filename;
        }
}
