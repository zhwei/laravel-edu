<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testIndex()
    {
        $resp = $this->get('/');
        $resp->assertSuccessful();
        self::assertTrue(is_array($resp->json()));
    }
}
