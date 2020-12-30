<?php

namespace Tests\Feature;

use App\Teacher;
use Faker\Generator;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function testRegister()
    {
        $faker = app(Generator::class);

        // fail
        $resp = $this->postJson('/auth/register', []);
        self::assertSame(['name', 'email', 'password'], array_keys($resp->json()['errors']));

        // success
        $resp = $this->postJson('/auth/register', [
            'name' => 'Tom',
            'email' => $mail = $faker->email,
            'password' => 'hello.world',
            'password_confirmation' => 'hello.world',
        ]);
        self::assertSame(['access_token', 'expires_at', 'name', 'role'], array_keys($resp->json()));
        self::assertNotNull(Teacher::whereEmail($mail)->first());
        self::assertSame('Tom', $resp->json()['name']);
        self::assertSame('teacher', $resp->json()['role']);
    }

    public function testLogin()
    {
        factory(Teacher::class)->create([
            'name' => 'hello world',
            'email' => $mail = app(Generator::class)->email,
            'password' => bcrypt($password = 'hello.world'),
        ]);

        // fail
        $resp = $this->postJson('/auth/login', ['email' => 'hello@world.com', 'password' => '123']);
        self::assertSame(400, $resp->getStatusCode());

        // success
        $resp = $this->postJson('/auth/login', [
            'email' => $mail,
            'password' => $password,
        ]);
        $resp->assertSuccessful();
        self::assertSame(['access_token', 'expires_at', 'name', 'role'], array_keys($resp->json()));
        self::assertTrue(is_int($resp->json()['expires_at']));
        self::assertSame('hello world', $resp->json()['name']);
    }
}
