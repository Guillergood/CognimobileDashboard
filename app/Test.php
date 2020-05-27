<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
  public $incrementing = true;
  protected $dates = [
      'updated_at',
      'created_at',
  ];

  protected $fillable = [
      'id',
      'name',
      'data',
      'created_at',
      'updated_at',
  ];
}
