<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EscrowWallet extends Model {
    public $timestamps = false;
    protected $fillable = ['organiser_id','held_balance','available_balance','total_withdrawn'];
    protected $casts = ['held_balance'=>'decimal:2','available_balance'=>'decimal:2','total_withdrawn'=>'decimal:2'];

    public function organiser() { return $this->belongsTo(User::class,'organiser_id'); }

    public function credit(float $amount): void {
        $this->increment('held_balance', $amount);
    }
    public function release(float $amount): void {
        $this->decrement('held_balance', $amount);
        $this->increment('available_balance', $amount);
        $this->touch();
    }
    public function withdraw(float $amount): void {
        $this->decrement('available_balance', $amount);
        $this->increment('total_withdrawn', $amount);
        $this->touch();
    }
    public function reverseFromHeld(float $amount): void {
        $this->decrement('held_balance', $amount);
        $this->touch();
    }
}

