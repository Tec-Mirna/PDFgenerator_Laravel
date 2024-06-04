<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'email',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'card_cvc',
        'amount',
    ];
}
