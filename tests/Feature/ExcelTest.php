<?php

namespace Tests\Feature;

use App\Jobs\ParseExcelFile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ExcelTest extends TestCase
{
    public function testUploadExcel(): void
    {
        \Storage::fake('local');
//        Queue::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $excelFileDir = base_path('/tests/fixtures/test.xlsx');
        $excelFile = UploadedFile::fake()->createWithContent('test.xlsx', file_get_contents($excelFileDir));

        $response = $this->postJson(route('excel.upload'), [
            'excel' => $excelFile,
        ]);

        $response->assertNoContent();
        Queue::assertPushed(ParseExcelFile::class, function () {

                dd();
            $this->assertDatabaseCount('rows', 3000);
        });
    }
}
