<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(NavigationMenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    public function allItems()
    {
        return $this->hasMany(NavigationMenuItem::class)->orderBy('order');
    }
}
