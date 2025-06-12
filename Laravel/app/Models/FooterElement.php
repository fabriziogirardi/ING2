<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @mixin QueryBuilder
 * @mixin EloquentBuilder
 */
class FooterElement extends Model
{
    /** @use HasFactory<\Database\Factories\FooterElementFactory> */
    use HasFactory;

    protected $fillable = [
        'icon',
        'link',
    ];

    public function getFormattedText(): string
    {
        if ($this->isUrl()) {
            return parse_url($this->text, PHP_URL_HOST);
        }

        return $this->text;
    }

    public function isUrl(): bool
    {
        return filter_var($this->text, FILTER_VALIDATE_URL) !== false;
    }
}
