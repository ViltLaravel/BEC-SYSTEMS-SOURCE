<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'id_sales';
    protected $guarded = [];

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id_branch', 'id_branch');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
