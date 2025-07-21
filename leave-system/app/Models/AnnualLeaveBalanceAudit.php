<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualLeaveBalanceAudit extends Model
{
    protected $fillable = [
        'user_id', 'action_by', 'action', 'description'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function actionBy() {
        return $this->belongsTo(User::class, 'action_by');
    }
}

