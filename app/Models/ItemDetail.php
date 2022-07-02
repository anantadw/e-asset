<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemDetail extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function detailTransaction()
    {
        return $this->hasOne(DetailTransaction::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
