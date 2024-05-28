<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepositWithdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'transactionType',
        'accountNo',
        'amount',
        'adminId'
    ];

    public function admin() : BelongsTo {
        return $this->belongsTo(Admin::class,'id','adminId');
    }
}
