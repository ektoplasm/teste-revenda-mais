<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cpf_cnpj',
        'name',
        'email',
        'address',
        'number',
        'city',
        'state',
        'address_info',
        'primary_contact',
        'primary_contact_email'
    ];
}
