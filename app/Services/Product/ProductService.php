<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\Barcode\BarcodeService;
use App\Services\ProductAttribute\ProductAttributeService;
use App\Services\ProductFile\ProductFileService;
use App\Services\ProductImage\ProductImageService;
use App\Services\ProductSpecialPrice\ProductSpecialPriceService;
use App\Services\ProductStock\ProductStockService;
use App\Services\Tax\TaxService;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(private readonly int $shopId)
    {
        //
    }

    public function list(?int $category = 0)
    {
        if (! isset($category) || $category === 0) {
            return Product::inShop($this->shopId)->get();
        } else {
            return Product::inShop($this->shopId)
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $category))
                ->get();
        }
    }

    public function listPublished(?int $category = 0)
    {
        if (! isset($category) || $category === 0) {
            return Product::inShop($this->shopId)->isPublished()->get();
        } else {
            return Product::inShop($this->shopId)
                ->isPublished()
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $category))
                ->get();
        }
    }

    public function listDraft(?int $category = 0)
    {
        if (! isset($category) || $category === 0) {
            return Product::inShop($this->shopId)->isDraft()->get();
        } else {
            return Product::inShop($this->shopId)
                ->isDraft()
                ->whereHas('categories', fn ($query) => $query->where('categories.id', $category))
                ->get();
        }
    }

    /**
     * Get one product
     *
     * @param  array<string | array<string, function>>|null  $withRelations
     */
    public function read(int $id, ?array $withRelations = []): Product
    {
        $product = Product::inShop($this->shopId)->findOrFail($id);

        if (count($withRelations) > 0) {
            $product->load($withRelations);
        }

        return $product;
    }

    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $taxService = new TaxService($this->shopId);

            /** @var \App\Models\Product $product */
            $product = new Product(
                collect($data)->only([
                    'state',
                    'price',
                    'cost_price',
                    'reference',
                    'supplier_reference',
                    'condition',
                    'name',
                    'summary',
                    'description',
                ])->toArray()
            );

            $product->tax_id = $taxService->read($data['tax_id'])->getKey();

            $product->save();

            $product->categories()->sync($data['categories']);

            if (isset($data['barcodes'])) {
                $barcodeIds = [];

                foreach ($data['barcodes'] as $barcodeData) {
                    $barcodeService = new BarcodeService($this->shopId);
                    $barcode = $barcodeService->create($barcodeData);
                    $barcodeIds[] = $barcode->getKey();
                }

                $product->barcodes()->sync($barcodeIds);
            }

            if (isset($data['product_attributes'])) {
                $productAttributeService = new ProductAttributeService($this->shopId);

                foreach ($data['product_attributes'] as $productAttributeData) {
                    $productAttribute = $productAttributeService->create($product, $productAttributeData);

                    $productAttribute->product()->associate($product);
                }
            }

            if (isset($data['special_prices'])) {
                $specialPriceModels = [];
                $specialPriceService = ProductSpecialPriceService::factory($this->shopId);

                foreach ($data['special_prices'] as $specialPriceData) {
                    $specialPriceModels[] = $specialPriceService->newModel($specialPriceData)->get();
                }

                $product->specialPrices()->saveMany($specialPriceModels);
            }

            if (isset($data['images'])) {
                $imageModels = [];
                $productImageService = new ProductImageService($this->shopId);

                foreach ($data['images'] as $imageData) {
                    $imageModels[] = $productImageService->newModel($imageData);
                }

                $product->images()->saveMany($imageModels);
            }

            if (isset($data['files'])) {
                $fileModels = [];
                $productFileService = ProductFileService::factory($this->shopId);

                foreach ($data['files'] as $fileData) {
                    $fileModels[] = $productFileService->newModel($fileData)->get();
                }

                $product->files()->saveMany($fileModels);
            }

            if (isset($data['stock'])) {
                /** @var \App\Models\ProductStock */
                $productStock = ProductStockService::factory($this->shopId)
                    ->newModel($data['stock'])
                    ->get();

                $product->stock()->save($productStock);
            }

            return $product;
        });
    }

    public function update(int $id, array $data): Product
    {
        return DB::transaction(function () use ($id, $data) {
            $product = Product::inShop($this->shopId)->findOrFail($id);
            $product->update($data);
            $product->categories()->sync($data['categories']);
            $product->barcodes()->sync($data['barcodes']);

            return $product;
        });
    }

    public function delete(int $id): bool
    {
        $product = Product::inShop($this->shopId)->findOrFail($id);

        return $product->delete();
    }
}
