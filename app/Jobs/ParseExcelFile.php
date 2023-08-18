<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Rap2hpoutre\FastExcel\FastExcel;
use Storage;

class ParseExcelFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $filePath, private readonly string $hash)
    {
    }

    public function handle(): void
    {
        $storage = Storage::disk('local');
        $processedRowCount = 0;
        $key = 'excel_import:' . $this->hash;
        $excelFile = $storage->path($this->filePath);

        (new FastExcel())->import($excelFile, function ($line) use (&$processedRowCount, $key) {
                $chunkSize = 1000;

                $processedRowCount++;

                if ($processedRowCount % $chunkSize === 0) {
                    Redis::set($key, $processedRowCount);
                }
            });
    }
}
