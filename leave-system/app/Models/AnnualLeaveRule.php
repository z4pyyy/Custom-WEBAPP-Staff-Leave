<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualLeaveRule extends Model
{
    protected $fillable = ['max_carry_forward_days', 'monthly_earning_rate'];
}
