<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'categories', key: 'category_id')]
#[Fillable(['category_id', 'category_name', 'parent_category_id'])]
class Category extends Model {}
