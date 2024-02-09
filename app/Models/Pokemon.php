<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'photo',
    ];

    protected function name(): Attribute
    {
        return new Attribute(
            get: function($value) {
                return strtoupper($value);
            },

            set: function($value) {
                return strtolower($value);
            }
        );
    }

    protected function type(): Attribute
    {
        return new Attribute(
            set: function($value) {
                return strtolower($value);
            }
        );
    }

    protected function photo(): Attribute
    {
        return new Attribute(
            set: function($value) {
                return strtolower($value);
            }
        );
    }
}
