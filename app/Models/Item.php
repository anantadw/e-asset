<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'stock',
        'admin_id',
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function itemDetails()
    {
        return $this->hasMany(ItemDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function incomingTransaction()
    {
        return $this->hasOne(IncomingTransaction::class);
    }
}
