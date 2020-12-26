<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersAddInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('is_student')
                ->default(0)
                ->index()
                ->comment('是否是学生，0：非学生，其他值：身份变成学生的时间戳');
            $table->unsignedInteger('student_school_id')
                ->default(0)
                ->index()
                ->comment('学生所属学校 id');
            $table->timestamp('is_teacher')
                ->default(0)
                ->index()
                ->comment('是否是老师，0：非老师，其他值：身份变成老师的时间戳');
            $table->timestamp('is_system_admin')
                ->default(0)
                ->comment('是否是系统管理员，0：非系统管理员，其他值：身份变成系统管理员的时间戳');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_student',
                'student_school_id',
                'is_teacher',
                'is_system_admin',
            ]);
        });
    }
}
