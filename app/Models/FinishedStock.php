<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedStock extends Model
{
    protected $table = 'finished_stocks';
    protected $fillable = [
        'machine_id', 'date', 'party_name', 'khana', 'b_width', 'b_length', 'sheets_per_bundle', 'sheet_size', 'lot', 'bundle'
    ];

    public function order()
    {
        // Assuming you have a way to relate finished stock to order, e.g. by lot or party_name
        return $this->hasOne(Order::class, 'lot', 'lot');
    }
}
