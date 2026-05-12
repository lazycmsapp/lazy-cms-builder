<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationMenuItem extends Model
{
    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(NavigationMenu::class, 'navigation_menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(NavigationMenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationMenuItem::class, 'parent_id')->orderBy('order');
    }
}
