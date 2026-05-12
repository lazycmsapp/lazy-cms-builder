<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'cms_forms';
    protected $guarded = [];

    protected $casts = [
        'fields' => 'array',
        'settings' => 'array',
        'status' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_id')->latest();
    }
}
