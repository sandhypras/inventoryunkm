<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'customer_name',
        'date',
        'total',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'total' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(StockOutItem::class);
    }

    public static function generateCode()
    {
        $lastRecord = self::latest('id')->first();
        $number = $lastRecord ? intval(substr($lastRecord->code, 3)) + 1 : 1;
        return 'SO-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
