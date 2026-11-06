<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model {
    protected $fillable = ['student_id','title','amount_due','amount_paid','due_date','paid_date','method','note'];
    protected $casts = ['due_date' => 'date','paid_date' => 'date'];
    public function student() { return $this->belongsTo(Student::class); }
    public function getDueAttribute(): float { return max(0, (float)$this->amount_due - (float)$this->amount_paid); }
}
