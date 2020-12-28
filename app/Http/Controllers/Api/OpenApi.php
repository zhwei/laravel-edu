<?php

namespace App\Http\Controllers\Api;

use OpenApi\Annotations\Info;
use OpenApi\Annotations\SecurityScheme;
use OpenApi\Annotations\Server;
use OpenApi\Annotations\Tag;

/**
 * Class OpenApi
 * @package App\Http\Controllers\Api
 *
 * @Info(title="Laravel Edu API", version="1.0")
 *
 * @Server(description="开发环境", url="http://127.0.0.1:8000")
 * @Server(description="生产环境", url="http://laravel-edu.zhw.in")
 *
 * @Tag(name="Auth", description="授权接口")
 * @Tag(name="Students", description="学生接口")
 * @Tag(name="Teachers", description="老师接口")
 * @Tag(name="Schools", description="学校接口")
 *
 * @SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */
interface OpenApi
{
    const PAGE_LIMIT = 100;
}
