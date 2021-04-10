<?php

namespace App\Managers;

use App\Helpers\InteractsWithFile;
use App\Jobs\ProductImportJob;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Group;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductImportManager
{
    use InteractsWithFile;

    const CATEGORY_INDEX = 2;
    const BRAND_INDEX = 3;
    const GROUP_INDEX = 4;

    protected array $products = [
        'category' => [],
        'brand' => [],
        'group' => [],
    ];

    public function bulkInsert(Collection $csvRows)
    {
        $rows = $csvRows->except(0);

//        $rows = $this->sanitizeRows($rows);

        $this->setRelationalKeys($rows);
        $this->saveCategories();
        $this->saveBrands();
        $this->saveGroups();

        $productRows = [];
        $variantRows = [];

        foreach ($rows as $row) {
            $slug = uniqid();
            array_push($productRows, [
                'title' => $row[1],
                'slug' => $slug,
                'type' => $row[5],
                'category_id' => $this->products['category'][$row[self::CATEGORY_INDEX]],
                'brand_id' => $this->products['brand'][$row[self::BRAND_INDEX]],
                'group_id' => $this->products['group'][$row[self::GROUP_INDEX]],
            ]);

            $variantRows[$slug] = [
                'name' => $row[5],
                'value' => $row[6],
                'sku' => $row[7],
                'barcode' => $row[8],
                'price' => $row[9],
            ];
        }

        Product::query()->insert($productRows);

        $productVariants = Product::query()->whereIn('slug', array_keys($variantRows))->pluck('id', 'slug')
            ->map(function ($id, $slug) use ($variantRows) {
                $row = $variantRows[$slug];
                $row['product_id'] = $id;
                 return $row;
            })->toArray();

        ProductVariant::query()->insert($productVariants);
    }

    private function setRelationalKeys($csvRows)
    {
        foreach ($csvRows as $row) {
            array_push($this->products['category'], $row[self::CATEGORY_INDEX]);
            array_push($this->products['group'], $row[self::GROUP_INDEX]);
            array_push($this->products['brand'], $row[self::BRAND_INDEX]);
        }
    }

    private function saveCategories()
    {
        $category_names = collect($this->products['category'])
            ->unique();

        $exists = Category::query()
            ->whereIn('title', $category_names)
            ->pluck('title');

        $notExists = $category_names->diff($exists)
            ->map(fn($title) => compact('title'))
            ->toArray();

        Category::query()->insert($notExists);

        $this->products['category'] = Category::query()
            ->whereIn('title', $category_names)
            ->pluck('id', 'title');
    }

    private function saveGroups()
    {
        $group_names = collect($this->products['group'])
            ->unique();

        $exists = Group::query()
            ->whereIn('title', $group_names)
            ->pluck('title');

        $notExists = $group_names->diff($exists)
            ->map(fn($title) => compact('title'))
            ->toArray();

        Group::query()->insert($notExists);

        $this->products['group'] = Group::query()
            ->whereIn('title', $group_names)
            ->pluck('id', 'title');
    }

    private function saveBrands()
    {
        $brand_names = collect($this->products['brand'])
            ->unique();

        $exists = Brand::query()
            ->whereIn('title', $brand_names)
            ->pluck('title');

        $notExists = $brand_names->diff($exists)
            ->map(fn($title) => compact('title'))
            ->toArray();

        Brand::query()->insert($notExists);

        $this->products['brand'] = Brand::query()
            ->whereIn('title', $brand_names)
            ->pluck('id', 'title');
    }

    public function import($file_path)
    {
        $file = $this->readFile($file_path);

        $chunks = $this->collectCSV($file)->chunk(5000);

        foreach ($chunks as $chunk) {
            $is_last = !next( $chunks )
                ? $this->getImportingFile($file)
                : false;

            ProductImportJob::dispatch($chunk, $is_last)
                ->onConnection('database');

            usleep(1000);
        }

        $this->file($file)
            ->move($this->importingDirectory());
    }

    private function readFile($file_path): UploadedFile
    {
        return $this->pathToUploadedFile($file_path);
    }

    private function collectCSV($file): Collection
    {
        return collect((array_map('str_getcsv', file($file))));
    }

    private function importingDirectory(): string
    {
        $path = 'app'. DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR . 'product';
        return storage_path($path) . DIRECTORY_SEPARATOR . "importing";
    }

    private function getImportingFile($file): array
    {
        return [
            'directory' => $this->importingDirectory(),
            'file_name' => $file->getFilename()
        ];
    }

//    private function sanitizeRows(Collection $rows)
//    {
//        $validator = Validator::make($rows->toArray(), [
//            '7.*' => 'distinct',
//            '8.*' => 'distinct',
//        ]);
//
//        if ($validator->fails()) {
//            logger($validator->errors());
//        }
//
//        return $rows;
//    }
}
