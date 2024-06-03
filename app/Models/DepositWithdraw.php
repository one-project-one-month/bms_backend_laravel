<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepositWithdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'process',
        'accountNo',
        'amount',
        'adminId',
        'date',
        'time'
    ];

    public function admin() : BelongsTo {
        return $this->belongsTo(Admin::class, 'adminId');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accountNo');
    }
}
