<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

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
    public function user()
    {
        $this->belongsTo(User::class);
    }

    /**
     * @return HasMany|void
     */
    public function payments()
    {
        $this->hasMany(Payment::class);
    }
}
