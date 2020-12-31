<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testIndex()
    {
        $resp = $this->get('/');
        self::assertSame(302, $resp->getStatusCode());
    }

    public function testApi()
    {
        $resp = $this->get('/api');
        $resp->assertSuccessful();
        self::assertTrue(is_array($resp->json()));
    }
}
