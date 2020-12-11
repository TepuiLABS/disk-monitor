<?php
namespace Tepuilabs\LaravelDiskMonitor\Tests\Feature\Commands;

use Illuminate\Support\Facades\Storage;
use Tepuilabs\LaravelDiskMonitor\Commands\RecordDiskMetricsCommand;
use Tepuilabs\LaravelDiskMonitor\Models\DiskMonitorEntry;
use Tepuilabs\LaravelDiskMonitor\Tests\TestCase;

class RecordDiskMetricsCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('anotherDisk');
    }

    /** @test */
    public function it_will_record_zero_file_count_for_a_disk()
    {
        $this->artisan(RecordDiskMetricsCommand::class)
            ->assertExitCode(0);
        $entry = DiskMonitorEntry::last();
        $this->assertEquals(0, $entry->file_count);
    }

    /** @test */
    public function it_will_record_one_file_count_for_a_disk()
    {
        Storage::disk('local')->put('test.txt', 'text');

        $this->artisan(RecordDiskMetricsCommand::class)
            ->assertExitCode(0);
        $otherEntry = DiskMonitorEntry::last();
        $this->assertEquals(1, $otherEntry->file_count);
    }

    /** @test */
    public function it_will_record_the_file_count_for_multiple_disks()
    {
        config()->set('laravel-disk-monitor.disk_names', [
            'local',
            'anotherDisk',
        ]);

        Storage::disk('anotherDisk')->put('test.txt', 'text');


        $this->artisan(RecordDiskMetricsCommand::class)
            ->assertExitCode(0);

        $entries = DiskMonitorEntry::get();

        $this->assertCount(2, $entries);

        $this->assertEquals('local', $entries[0]->disk_name);
        $this->assertEquals(0, $entries[0]->file_count);

        $this->assertEquals('anotherDisk', $entries[1]->disk_name);
        $this->assertEquals(1, $entries[1]->file_count);
    }
}
