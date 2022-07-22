<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Applications extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tdId',
        'applicantId',
        'name',
        'address',
        'classification',
        'assessedValue',
        'coordinates',
        'image',
        'status',
    ];

    
   
}
