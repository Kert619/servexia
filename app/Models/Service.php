<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'services', key: 'service_id')]
#[Fillable(['place_id', 'service_name', 'latitude', 'longitude', 'rating', 'reviews', 'unclaimed_listing', 'address', 'phone', 'website', 'reviews_link', 'photos_link', 'type', 'types', 'type_id', 'type_ids', 'operating_hours', 'user_review', 'thumbnail', 'valid_service'])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'types' => 'array',
            'type_ids' => 'array',
            'operating_hours' => 'array',
            'unclaimed_listing' => 'boolean',
            'valid_service' => 'boolean',
        ];
    }
}
