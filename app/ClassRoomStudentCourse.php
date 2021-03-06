<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassRoomStudentCourse extends Model
{

    protected $table    = "class_room_student_courses";
    protected $fillable = [
        "class_student_id", "class_course_id",
        "semester", "kkmpengetahuan",
        "kkmketerampilan", "nilaitugas",
        "nilaitugas_dua", "nilaitugas_tiga",
        "ulanganharian", "ulanganharian_dua",
        "ulanganharian_tiga", "uts", "uas", "keaktifan", "kerapian",
    ];

    //untuk menampikan hubungan antara classroom
    public function class_course()
    {
        return $this->belongsTo('App\ClassCourse');
    }
    //untuk menampilkan hubungan antara
    public function class_student()
    {
        return $this->belongsTo('App\ClassStudent')->withDefault(['student']);
    }
}
