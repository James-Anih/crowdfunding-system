<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable =[
        'userId',
        'name',
        'description',
        'targetAmount',
        'amountReceived',
        'status',
        'closeDate'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }


    public function donations():HasMany
    {
        return $this->hasMany(Donation::class, 'campaignId');
    }
}
