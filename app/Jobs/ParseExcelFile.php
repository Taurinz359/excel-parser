<?php

namespace App\Jobs;

use App\Models\Row;
use Carbon\Carbon;
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

    private const CHUNK_SIZE = 1000;

    public function __construct(private readonly string $filePath, private readonly string $hash)
    {
    }

    public function handle(): void
    {
        $storage = Storage::disk('local');
        $processedRowCount = 0;
        $key = 'excel_import:' . $this->hash;
        $excelFile = $storage->path($this->filePath);

        \Db::beginTransaction();
        (new FastExcel())->import($excelFile, function ($line) use (&$processedRowCount, $key) {
            Redis::set($key, $processedRowCount);
            $processedRowCount++;

            Row::create([
                'excel_id' => $line['id'],
                'name' => $line['name'],
                'date' => Carbon::parse($line['date'])->format('d.m.Y')
            ])->save();

            if ($processedRowCount % self::CHUNK_SIZE === 0) {
                \DB::commit();
                \DB::beginTransaction();
            }
        });
        \DB::commit();
    }
}
