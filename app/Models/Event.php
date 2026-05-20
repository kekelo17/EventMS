<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    protected $fillable = [
        'organiser_id','title','description','venue','event_date','end_date',
        'price','capacity','tickets_sold','banner_image','category','status','rejection_reason'
    ];
    protected $casts = ['event_date'=>'datetime','end_date'=>'datetime','price'=>'decimal:2'];

    public function organiser()   { return $this->belongsTo(User::class, 'organiser_id'); }
    public function tickets()     { return $this->hasMany(Ticket::class); }
    public function paidTickets() { return $this->tickets()->where('status','paid'); }
    public function availableSeats() { return $this->capacity - $this->tickets_sold; }
    public function isSoldOut()   { return $this->availableSeats() <= 0; }
    public function totalRevenue(){ return $this->tickets()->where('status','paid')->sum('total_price'); }
    public function scopeApproved($q){ return $q->where('status','approved'); }
    public function scopePending($q) { return $q->where('status','pending'); }
}