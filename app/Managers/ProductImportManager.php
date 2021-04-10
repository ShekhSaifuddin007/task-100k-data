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

class ProductImportManager
{
    use InteractsWithFile;

    protected array $products = [
        'category' => [],
        'brand' => [],
        'group' => [],
    ];

    public function bulkInsert(Collection $csvRows)
    {
        $this->setRelationalKeys($csvRows->except(0));

        $this->saveCategories();
        $this->saveBrands();
        $this->saveGroups();

        Product::withoutEvents(function () use($csvRows) {
            foreach ($csvRows as $row) {
                $product = Product::query()->create([
                    'title' => $row[0],
                    'type' => $row[4],
                    'category_id' => $this->products['category'][$row[1]],
                    'brand_id' => $this->products['brand'][$row[2]],
                    'group_id' => $this->products['group'][$row[3]],
                ]);

                ProductVariant::withoutEvents(fn () =>
                    ProductVariant::query()->create([
                        'product_id' => $product->id,
                        'name' => $row[5],
                        'value' => $row[6],
                        'sku' => $row[7],
                        'barcode' => $row[8],
                        'price' => $row[9],
                   ])
                );
            }
        });
    }

    private function setRelationalKeys($csvRows)
    {
        foreach ($csvRows as $row) {
            $this->products['category'] = $row[1];
            $this->products['brand'] = $row[2];
            $this->products['group'] = $row[3];
        }
    }

    private function saveCategories()
    {
        $category_names = collect($this->products['category'])
            ->unique();

        $exists = Category::query()
            ->whereIn('title', $category_names)
            ->pluck('title');

        $notExists = $exists->diff($category_names)
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

        $notExists = $exists->diff($group_names)
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

        $notExists = $exists->diff($brand_names)
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
        $this->file($file)
            ->move($this->importingDirectory());

        $chunks = $this->collectCSV($file)->chunk(10000);

        foreach ($chunks as $chunk) {
            $is_last = !next( $chunks )
                ? $this->getImportingFile($file)
                : false;

            ProductImportJob::dispatch($chunk, $is_last)
                ->onConnection('database');
        }
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
        return storage_path('app/public/product') . DIRECTORY_SEPARATOR . "importing";
    }

    private function getImportingFile($file): array
    {
        return [
            'directory' => $this->importingDirectory(),
            'file_name' => $file->getFilename()
        ];
    }
}
