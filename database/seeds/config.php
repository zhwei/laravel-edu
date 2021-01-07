<?php

return [

    // 默认密码：secret
    UserSeeder::class => [
        [
            'email' => 'tom.student@jerry.com',
            'line_id' => 'U88785bc472841e0c0624640995f92aff',
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

    AdminMenuSeeder::class => [
        ['parent_id' => 0, 'order' => 100, 'icon' => 'fa-user', 'uri' => 'edu/students', 'title' => 'Edu Students'],
        ['parent_id' => 0, 'order' => 101, 'icon' => 'fa-user', 'uri' => 'edu/teachers', 'title' => 'Edu Teachers'],
        ['parent_id' => 0, 'order' => 102, 'icon' => 'fa-user', 'uri' => 'edu/users', 'title' => 'Edu Users'],
        ['parent_id' => 0, 'order' => 103, 'icon' => 'fa-list', 'uri' => 'edu/schools', 'title' => 'Edu Schools'],
    ],
];
