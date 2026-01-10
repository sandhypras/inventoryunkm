<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'supplier_id',
        'date',
        'total',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'total' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(StockInItem::class);
    }

    public static function generateCode()
    {
        $lastRecord = self::latest('id')->first();
        $number = $lastRecord ? intval(substr($lastRecord->code, 3)) + 1 : 1;
        return 'SI-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
