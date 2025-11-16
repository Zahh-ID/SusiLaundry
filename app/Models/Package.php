<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_name',
        'description',
        'price_per_kg',
        'billing_type',
        'turnaround_hours',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
