<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable {
 protected $fillable=['name','email','password','role','student_id'];
 protected $hidden=['password'];
 public function student(){ return $this->belongsTo(Student::class); }
}
