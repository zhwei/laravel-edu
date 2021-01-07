<?php


use Encore\Admin\Auth\Database\Menu;

class AdminMenuSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $rows = (require __DIR__ . '/config.php')[self::class];
        Menu::query()->insert($rows);
    }
}
