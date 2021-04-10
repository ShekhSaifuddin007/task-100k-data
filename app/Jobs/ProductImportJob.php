<?php

namespace App\Jobs;

use App\Managers\ProductImportManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection $products;
    private $is_not_last;

    public function __construct(
        Collection $products,
        $is_not_last
    ) {
        $this->products = $products;
        $this->is_not_last = $is_not_last;
    }


    public function handle()
    {
        resolve(ProductImportManager::class)
            ->bulkInsert($this->products);

        if ($file = $this->is_not_last) {
            $from = $file['directory'] . DIRECTORY_SEPARATOR . $file['file_name'];
            $to = str_replace('importing', 'imported', $from);
            copy($from, $to);
            unlink($from);
        }
    }
}
