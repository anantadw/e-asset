<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingTransaction extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
