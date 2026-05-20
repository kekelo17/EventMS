<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model {
    protected $fillable = [
        'organiser_id','event_id','amount_requested','available_balance',
        'bank_name','account_number','account_name','mobile_money_number',
        'payment_channel','status','admin_note','processed_by','processed_at'
    ];
    protected $casts = ['processed_at'=>'datetime','amount_requested'=>'decimal:2','available_balance'=>'decimal:2'];

    public function organiser()   { return $this->belongsTo(User::class,'organiser_id'); }
    public function event()       { return $this->belongsTo(Event::class); }
    public function processedBy() { return $this->belongsTo(User::class,'processed_by'); }
}
