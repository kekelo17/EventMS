<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model {
    protected $fillable = [
        'payment_id','user_id','reason','amount_requested',
        'status','admin_note','processed_by','processed_at'
    ];
    protected $casts = ['processed_at'=>'datetime','amount_requested'=>'decimal:2'];

    public function payment()     { return $this->belongsTo(Payment::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function processedBy() { return $this->belongsTo(User::class,'processed_by'); }
}
