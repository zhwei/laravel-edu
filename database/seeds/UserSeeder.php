<?php


use App\Student;

class UserSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $mail = 'tom.student@jerry.com';
        if (Student::whereEmail($mail)->first()) {
            echo "tom.student exists\n";
            return;
        }

        Student::forceCreate([
            'name' => 'Tom',
            'email' => $mail,
            'password' => bcrypt('secret'),
            'is_student' => time(),
        ]);
    }
}
