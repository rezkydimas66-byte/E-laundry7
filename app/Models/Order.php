<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 
        'invoice_code', 
        'order_date', 
        'completion_date', 
        'status', 
        'total_price'
    ];
    protected $casts = [
        'order_date' => 'date',
        'pickup_date' => 'date',
        'completed_at' => 'datetime',
    ];
    /**
     * Relasi balik ke Customer (Setiap order dimiliki oleh satu customer)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi many-to-many ke Service melalui pivot table order_details
     * Kita juga menyertakan kolom 'qty' dan 'subtotal' yang ada di tabel pivot.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_details', 'order_id', 'service_id')
            ->withPivot('qty', 'subtotal')
            ->withTimestamps();
    }
}
