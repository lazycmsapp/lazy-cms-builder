<?php

namespace Acme\CmsDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireSalePrices extends Command
{
    protected $signature = 'lazy:expire-sales';
    protected $description = 'Null out sale prices whose sale_ends_at date has passed.';

    public function handle(): int
    {
        $count = DB::table('shop_products')
            ->whereNotNull('sale_ends_at')
            ->where('sale_ends_at', '<=', now())
            ->whereNotNull('sale_price')
            ->update(['sale_price' => null, 'sale_ends_at' => null]);

        $this->info("Expired {$count} sale price(s).");
        return 0;
    }
}
