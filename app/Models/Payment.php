<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = [
        'ticket_id','user_id','transaction_ref','amount','currency','payment_method',
        'escrow_status','card_last4','card_brand','gateway_response',
        'released_by','released_at','refunded_by','refunded_at','refund_amount','notes'
    ];
    protected $casts = [
        'amount'=>'decimal:2','refund_amount'=>'decimal:2',
        'gateway_response'=>'array',
        'released_at'=>'datetime','refunded_at'=>'datetime'
    ];

    public function ticket()       { return $this->belongsTo(Ticket::class); }
    public function user()         { return $this->belongsTo(User::class); }
    public function refundRequest(){ return $this->hasOne(RefundRequest::class); }
    public function releasedBy()   { return $this->belongsTo(User::class,'released_by'); }
    public function refundedBy()   { return $this->belongsTo(User::class,'refunded_by'); }

    public function isHeld()       { return $this->escrow_status === 'held'; }
    public function isReleased()   { return $this->escrow_status === 'released_to_organiser'; }
    public function isRefunded()   { return in_array($this->escrow_status,['refunded_to_client','partial_refund']); }

    public static function generateRef(): string {
        return 'PAY-'.strtoupper(uniqid('',true));
    }
}
