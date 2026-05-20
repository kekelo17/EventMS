<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $fillable = [
        'reference','type','amount','from_user_id','to_user_id',
        'related_id','related_type','status','description','performed_by'
    ];
    protected $casts = ['amount'=>'decimal:2'];

    public function fromUser()    { return $this->belongsTo(User::class,'from_user_id'); }
    public function toUser()      { return $this->belongsTo(User::class,'to_user_id'); }
    public function performedBy() { return $this->belongsTo(User::class,'performed_by'); }
}