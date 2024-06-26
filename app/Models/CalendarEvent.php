<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
  use HasFactory;

  protected $fillable = [
    'date', // Add 'date' to the fillable attributes
    'event',
    'time',
  ];
}
