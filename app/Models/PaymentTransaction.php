<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PaymentTransaction extends Model { protected $fillable=['payment_id','amount','paid_date','method','transaction_id','note']; protected $casts=['paid_date'=>'date']; public function payment(){return $this->belongsTo(Payment::class);} }
