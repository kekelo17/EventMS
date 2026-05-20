<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $fillable = [
        'event_id','user_id','ticket_code','qr_code',
        'quantity','unit_price','total_price','status','reserved_at','paid_at'
    ];
    protected $casts = ['paid_at'=>'datetime','reserved_at'=>'datetime'];

    public function event()   { return $this->belongsTo(Event::class); }
    public function user()    { return $this->belongsTo(User::class); }
    public function payment() { return $this->hasOne(Payment::class); }

    public static function generateCode(): string {
        return strtoupper('TKT-'.substr(uniqid('',true),0,8).'-'.random_int(1000,9999));
    }
}
