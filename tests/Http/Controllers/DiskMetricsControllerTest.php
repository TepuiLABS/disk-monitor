<?php
namespace Tepuilabs\LaravelDiskMonitor\Tests\Http\Controllers;

use Tepuilabs\LaravelDiskMonitor\Tests\TestCase;

class DiskMetricsControllerTest extends TestCase
{
    /** @test */
    public function it_can_display_the_list_of_entries()
    {
        $this->get('disk-monitor')->assertOk();
    }
}
