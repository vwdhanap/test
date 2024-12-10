<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckList extends Model
{
    /** @use HasFactory<\Database\Factories\CheckListFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'status'
    ];

    protected $cast = [
        'status' => 'boolean'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CheckList::class, 'parent_id');
    }

    public function child(): HasMany
    {
        return $this->hasMany(CheckList::class, 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
