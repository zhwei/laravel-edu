<?php

namespace Tests\Unit;

use App\Services\LineService;
use App\User;
use Tests\TestCase;

class LineTest extends TestCase
{
    public function testSendTextMessage()
    {
        $line = new LineService();
        // $line->broadcastTextMessage('hello world');
        $uid = User::where('line_id', '!=', '')->pluck('line_id')->first();
        // $line->multicastTextMessage([$uid], 'hello multicast');
    }
}
