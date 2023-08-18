<?php

namespace App\Http\Controllers;

use App\Jobs\ParseExcelFile;
use App\Models\Row;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ExcelController
{
    public function index(): View
    {
        $groupedData = Row::orderBy('date', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->date->format('Y-m-d');
            });
        return view('index', compact('groupedData'));
    }

    public function upload(Request $request): Response
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls'
        ]);

        $hash = uniqid();
        $savedPath = \Storage::disk('local')->putFileAs(
            "excel/$hash",
            $request->file('excel'),
            $request->file('excel')->getClientOriginalName()
        );

        ParseExcelFile::dispatch($savedPath, $hash);
        return response()->noContent();
    }
}
