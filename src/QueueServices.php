<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/QueueService.php';
require_once __DIR__ . '/../src/SmsService.php';

final class QueueServiceTest extends TestCase
{
    public function testEstimateWaitMultipliesPeopleAheadByAverage(): void
    {
        $this->assertSame(45, QueueService::estimateWait(3, 15));
    }

    public function testEstimateWaitIsZeroWhenNobodyAhead(): void
    {
        $this->assertSame(0, QueueService::estimateWait(0, 15));
    }

    public function testEstimateWaitNeverNegative(): void
    {
        $this->assertSame(0, QueueService::estimateWait(-2, 15));
    }

    public function testSortQueuePutsEmergencyFirstThenByNumber(): void
    {
        $entries = [
            ['queue_number' => 1, 'is_emergency' => 0],
            ['queue_number' => 2, 'is_emergency' => 0],
            ['queue_number' => 5, 'is_emergency' => 1],
            ['queue_number' => 4, 'is_emergency' => 1],
        ];
        $sorted = array_column(QueueService::sortQueue($entries), 'queue_number');
        $this->assertSame([4, 5, 1, 2], $sorted);
    }

    public function testPhoneConvertedToInternationalFormat(): void
    {
        $this->assertSame('+233244123456', SmsService::toInternational('0244123456'));
        $this->assertSame('+233244123456', SmsService::toInternational('233244123456'));
        $this->assertSame('+233244123456', SmsService::toInternational('+233244123456'));
        $this->assertSame('+233244123456', SmsService::toInternational('024 412 3456'));
    }

    public function testInvalidPhoneIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SmsService::toInternational('123');
    }
}
