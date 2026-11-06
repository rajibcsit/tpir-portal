<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
class Student extends Model {
 protected $fillable=['student_id','name','profile_photo','department','technology','semester','shift','session','phone','email','guardian_name','guardian_phone','address','joining_date','status'];
 protected $casts=['joining_date'=>'date'];
 public function getProfilePhotoUrlAttribute(): ?string { return $this->profile_photo && Storage::disk('public')->exists($this->profile_photo) ? route('students.photo', $this) : null; }
 public function attendances(): HasMany { return $this->hasMany(Attendance::class); }
 public function payments(): HasMany { return $this->hasMany(Payment::class); }
 public function examResults(): HasMany { return $this->hasMany(ExamResult::class); }
 public function totalFees(): float { return (float)$this->payments()->sum('amount_due'); }
 public function totalPaid(): float { return (float)$this->payments()->sum('amount_paid'); }
 public function dueAmount(): float { return max(0,$this->totalFees()-$this->totalPaid()); }
}
