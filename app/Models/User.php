<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['name','email','password','role','phone','avatar','is_active','email_verified_at'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime','is_active'=>'boolean'];

    public function events()       { return $this->hasMany(Event::class, 'organiser_id'); }
    public function tickets()      { return $this->hasMany(Ticket::class); }
    public function payments()     { return $this->hasMany(Payment::class); }
    public function refundRequests(){ return $this->hasMany(RefundRequest::class); }
    public function withdrawals()  { return $this->hasMany(WithdrawalRequest::class, 'organiser_id'); }
    public function wallet()       { return $this->hasOne(EscrowWallet::class, 'organiser_id'); }
    public function notifications(){ return $this->hasMany(Notification::class); }
    public function unreadNotifications(){ return $this->notifications()->where('is_read',0); }

    public function isAdmin()      { return $this->role === 'admin'; }
    public function isOrganiser()  { return $this->role === 'organiser'; }
    public function isClient()     { return in_array($this->role, ['client','organiser','admin']); }
}
