<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
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

        foreach (array_keys(require __DIR__ . '/config.php') as $seeder) {
            $this->call($seeder);
        }
    }
}
