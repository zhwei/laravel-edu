<?php


use App\Student;
use App\User;

class UserSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $roles = [
            'student',
            'teacher',
            'system_admin',
        ];
        foreach ($roles as $role) {
            $mail = "tom.{$role}@jerry.com";
            if (User::whereEmail($mail)->first()) {
                echo "tom.{$role} exists\n";
                continue;
            }
            User::forceCreate([
                'name' => 'Tom ' . ucfirst($role),
                'email' => $mail,
                'password' => bcrypt('secret'),
                "is_{$role}" => time(),
            ]);
        }
    }
}
