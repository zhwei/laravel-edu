<?php

namespace Tests\Feature;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MessageApiTest extends TestCase
{
    public function testStudentTalk()
    {
        Passport::actingAs(User::find(1));

        $resp = $this->postJson('/messages/student-talk/2', ['message' => 'hello world 11']);
        throw_if($resp->exception, $resp->exception);
        $resp->assertSuccessful();
    }
}
