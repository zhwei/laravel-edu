<?php

return [

    // 默认密码：secret
    UserSeeder::class => [
        [
            'email' => 'tom.student@jerry.com',
            '@role' => 'student',
            '@following' => ['tom.teacher@jerry.com', 'tom.teacher.2@jerry.com'],
            '@school' => '清华大学',
        ],
        ['email' => 'tom.teacher@jerry.com', '@role' => 'teacher'],
        ['email' => 'tom.teacher.2@jerry.com', '@role' => 'teacher'],
        ['email' => 'tom.school@jerry.com', '@role' => 'teacher'],
        ['email' => 'tom.system_admin@jerry.com', '@role' => 'system_admin'],
    ],

    SchoolSeeder::class => [
        ['name' => '清华大学', '@teachers' => ['tom.teacher@jerry.com']],
        ['name' => '北京大学', '@teachers' => ['tom.teacher@jerry.com', 'tom.teacher.2@jerry.com']],
        ['name' => '山东大学', '@teachers' => ['tom.teacher.2@jerry.com']],
        [
            'name' => '复旦大学',
            '@teachers' => ['tom.teacher.2@jerry.com'],
            '@managers' => ['tom.teacher.2@jerry.com'],
            '@creator' => 'tom.school@jerry.com',
        ],
    ],


];
