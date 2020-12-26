<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_follows', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id')
                ->index()
                ->comment('学生 id');
            $table->unsignedInteger('teacher_id')
                ->index()
                ->comment('教师 id');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_follows');
    }
}
