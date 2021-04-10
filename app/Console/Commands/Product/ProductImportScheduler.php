<?php

namespace App\Console\Commands\Product;

use App\Managers\ProductImportManager;
use Illuminate\Console\Command;

class ProductImportScheduler extends Command
{
    protected $signature = 'product:import';


    protected $description = 'This command will import products';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $folder =  storage_path('/app/public/product/importable');

        $firstFile = isset(scandir( $folder)[2]) ? scandir( $folder)[2] : false;

        if ($firstFile) {

            resolve(ProductImportManager::class)
                ->import($folder . DIRECTORY_SEPARATOR . $firstFile);
        }



    }
}
