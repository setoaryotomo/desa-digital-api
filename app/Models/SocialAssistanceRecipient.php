<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistanceRecipient extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'social_assistance_id',
        'head_of_family_id',
        'amount',
        'reason',
        'bank',
        'account_number',
        'proof',
        'status'
    ];

    public function scopeSearch($querry, $search)
    {
        return $querry->whereHas('headOfFamily', function ($querry) use ($search) {
            $querry->whereHas('user', function ($querry) use ($search) {
                $querry->where('name', 'like', '%' . $search . '%');
                $querry->orWhere('email', 'like', '%' . $search . '%');
            });
        });
    }

    public function socialAssistance()
    {
        return $this->belongsTo(SocialAssistance::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}