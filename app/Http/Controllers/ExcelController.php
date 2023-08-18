<?php

namespace App\Http\Controllers;

use App\Jobs\ParseExcelFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExcelController
{
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
