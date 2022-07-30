<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
class Payment extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'transaction_id',
        'amount',
        'payment_for',
        'payment_method',
        'payment_status',
        'date_of_payment',
        'tdId',
        'ownerId',
    ];
}
