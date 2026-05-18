<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mall extends Model
{
    protected $fillable = [
        'party', 'input1', 'input2', 'input3', 'input4', 'input5', 'input7', 'lot','newqty','availableqty'
    ];

    
   public function orders()
{
    return $this->hasMany(Order::class);
}

}
