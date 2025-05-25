<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'category_id',
        'price',
    ];

    /**
     * @return BelongsTo|void
     */
    public function expense()
    {
        $this->belongsTo(Expense::class);
    }

    /**
     * @return BelongsTo|void
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
