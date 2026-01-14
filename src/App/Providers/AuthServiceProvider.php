<?php

namespace App\Providers;

use App\Policies\ShoppingListItemPolicy;
use App\Policies\ShoppingListPolicy;
use Domain\ShoppingLists\Models\ShoppingList;
use Domain\ShoppingLists\Models\ShoppingListItem;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ShoppingList::class => ShoppingListPolicy::class,
        ShoppingListItem::class => ShoppingListItemPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
