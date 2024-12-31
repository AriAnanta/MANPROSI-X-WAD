<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['emisi_carbon_id', 'comment'];

    public function emisiCarbon()
    {
        return $this->belongsTo(EmisiCarbon::class);
    }
}
