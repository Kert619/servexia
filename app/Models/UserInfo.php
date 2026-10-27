<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(name: 'users_info', key: 'user_info_id')]
#[Fillable(['user_id', 'user_info_first_name', 'user_info_last_name', 'user_info_phone', 'user_info_address', 'user_info_region', 'user_info_province', 'user_info_city'])]
class UserInfo extends Model
{
    use SoftDeletes;

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
