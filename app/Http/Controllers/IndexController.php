<?php

namespace App\Http\Controllers;

use function OpenApi\scan;

class IndexController
{
    public function index()
    {
        return response(
            scan(app_path())->toJson(),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
