<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;

    public function outgoingTransaction()
    {
        return $this->belongsTo(OutgoingTransaction::class, 'transaction_id');
    }

    public function itemDetail()
    {
        return $this->belongsTo(ItemDetail::class);
    }
}
