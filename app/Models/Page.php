<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'text',
        'is_visible_in_header',
        'is_visible_in_footer',
    ];
}
