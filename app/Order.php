<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'payment_total', 'note'
    ];

    public function setPending()
    {
        $this->status = 'PENDING';
        $this->save();
    }

    public function setSuccess()
    {
        $this->status = 'SUCCESS';
        $this->save();
    }

    public function setFailed()
    {
        $this->status = 'FAILED';
        $this->save();
    }

    public function setExpired()
    {
        $this->status = 'EXPIRED';
        $this->save();
    }
}
