<?php

namespace App\Models;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
