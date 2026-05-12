<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    protected $table = 'custom_fields';
    protected $guarded = [];

    protected $casts = [
        'params' => 'array',
        'required' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class, 'field_group_id');
    }
}
