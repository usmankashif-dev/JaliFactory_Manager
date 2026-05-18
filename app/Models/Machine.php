<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;
    protected $table = 'machine';
    protected $fillable = [
        'machineqty',
        'machinedate',
        'machinenumber',
        'olenght',
        'peice',
        'ogauge',
        'cutsheet',
        'lot',
        'bundlewidht',
        'sheetperbundle',
        'partyorder',
        'jalilenght',
        'orderedpeices',
    ];
}
