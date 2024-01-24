<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory;

    protected $table = 'purchase_details';
    protected $primaryKey = 'id_purchase_detail';
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Product::class, 'id_product', 'id_product');
    }
}
