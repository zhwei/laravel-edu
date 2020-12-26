<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentFollow extends Model
{
    const UPDATED_AT = null;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
