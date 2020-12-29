<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private $seeders = [

        UserSeeder::class,

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // load seeder files
        $current = basename(__FILE__);
        foreach (scandir(__DIR__) as $filename) {
            if (Str::endsWith($filename, '.php') && $filename !== $current) {
                require __DIR__ . '/' . $filename;
            }
        }

        foreach ($this->seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
