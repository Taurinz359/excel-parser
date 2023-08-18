<?php

namespace Tests\Feature;

use App\Jobs\ParseExcelFile;
use App\Models\Row;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ExcelTest extends TestCase
{
    public function testIndex(): void
    {
        $needleCount = 10;
        $user = User::factory()->create();
        $this->actingAs($user);
        Row::factory()->count(10)->create();

        $response = $this->get(route('excel.index'))
            ->assertOk();

        $response->assertViewHas('groupedData', fn($groupedData) => $groupedData->count() === $needleCount);
    }

    public function testUploadExcel(): void
    {
        $excelRowsCount = 2474;
        \Storage::fake('local');
        Queue::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $excelFileDir = base_path('/tests/fixtures/test.xlsx');
        $excelFile = UploadedFile::fake()->createWithContent('test.xlsx', file_get_contents($excelFileDir));

        $response = $this->postJson(route('excel.upload'), [
            'excel' => $excelFile,
        ]);

        $response->assertNoContent();

        Queue::assertPushed(ParseExcelFile::class);
        Queue::after(fn() => $this->assertDatabaseCount('rows', $excelRowsCount));
    }
}
