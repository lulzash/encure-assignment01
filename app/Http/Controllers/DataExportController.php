<?php

namespace App\Http\Controllers;

use App\Exports\IdsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class DataExportController extends Controller
{
    public function export(Request $request)
    {
        $ids = $this->fetchAllRecords();

        return Excel::download(new IdsExport($ids->all()), 'unique-ids.xlsx');
    }

    public function fetchAllRecords()
    {
        $allIds = collect();
        $start = 0;
        $rows = 100;
        $totalResults = 0;

        set_time_limit(-1);

        $baseUrl = 'https://opencontext.org/query/Asia/Turkey/Kenan+Tepe.json';

        do {
            $response = Http::withOptions([
                    'verify' => false
                ])->get($baseUrl, [
                    'rows' => $rows,
                    'start' => $start
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $totalResults = $data['totalResults'] ?? 0;
                $ids = collect($data['features'])->pluck('id');
                $allIds = $allIds->merge($ids);
                $start += $rows;
                dump("Fetched {$start} records");
            } else {
                break;
            }
        } while ($start < $totalResults);

        return $allIds->unique();
    }
}
