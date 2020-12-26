<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('school_id')
                ->index()
                ->comment('学校 id');
            $table->unsignedInteger('teacher_id')
                ->index()
                ->comment('教师 id');
            $table->timestamp('is_manager')
                ->default(0)
                ->comment('是否学校管理员');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_teachers');
    }
}
