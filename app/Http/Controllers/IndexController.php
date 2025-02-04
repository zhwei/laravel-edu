<?php

namespace App\Http\Controllers;

use function OpenApi\scan;

class IndexController
{
    public function index()
    {
        return redirect('/dashboard/index.html');
    }

    public function api()
    {
        return response(
            scan(app_path())->toJson(),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
