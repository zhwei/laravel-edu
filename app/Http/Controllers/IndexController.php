<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function OpenApi\scan;

class IndexController
{
    public function index(Request $request)
    {
        $openapi = scan(app_path());
        if ($request->has('yaml')) {
            return response($openapi->toYaml(), 200, ['Content-Type' => 'application/x-yaml']);
        } else {
            return response($openapi->toJson(), 200, ['Content-Type' => 'application/json']);
        }
    }
}
