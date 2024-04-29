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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::beginTransaction();
        try {
            $taxService = new TaxService($this->shopId);

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
                $product = $this->attachBarcodes($product, $data['barcodes']);
            }

            if (isset($data['product_attributes'])) {
                $product = $this->associateProductAttributes($product, collect($data['product_attributes']));
            }

            if (isset($data['special_prices'])) {
                $product = $this->associateSpecialPrices($product, collect($data['special_prices']));
            }

            if (isset($data['images'])) {
                $product = $this->associateImages($product, collect($data['images']));
            }

            if (isset($data['files'])) {
                $product = $this->associateFiles($product, collect($data['files']));
            }

            if (isset($data['stock'])) {
                $productStock = ProductStockService::factory($this->shopId)
                    ->newModel($data['stock'])
                    ->get();

                $product->stock()->save($productStock);
            }

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function update(int $id, array $data): Product
    {
        Log::info('JSON: '.json_encode($data));
        DB::beginTransaction();
        try {
            $product = Product::inShop($this->shopId)->findOrFail($id);
            $product->update(collect($data)->only([
                'state',
                'price',
                'cost_price',
                'reference',
                'supplier_reference',
                'condition',
                'name',
                'summary',
                'description',
            ])->toArray());
            $product->categories()->sync($data['categories']);

            if ($product->tax_id !== $data['tax_id']) {
                $product = $this->setTaxId($product, $data['tax_id']);
            }

            if (isset($data['barcodes'])) {
                $product = $this->attachBarcodes($product, $data['barcodes']);
            } elseif ($product->barcodes->count() > 0) {
                $product = $this->detachBarcodes($product);
            }

            if (isset($data['product_attributes'])) {
                $collection = collect($data['product_attributes']);
                $product = $this->associateProductAttributes($product, $collection);
                $product = $this->deleteProductAttributesIfNeeded($product, $collection);
            } elseif ($product->productAttributes->count() > 0) {
                $product = $this->deleteProductAttributes($product);
            }

            if (isset($data['special_prices'])) {
                $collection = collect($data['special_prices']);
                $product = $this->associateSpecialPrices($product, $collection);
                $product = $this->deleteSpecialPricesIfNeeded($product, $collection);
            } elseif ($product->specialPrices->count() > 0) {
                $product = $this->deleteSpecialPrices($product);
            }

            if (isset($data['images'])) {
                $collection = collect($data['images']);
                $product = $this->associateImages($product, $collection);
                $product = $this->deleteImagesIfNeeded($product, $collection);
            } elseif ($product->images->count() > 0) {
                $product = $this->deleteImages($product);
            }

            if (isset($data['files'])) {
                $collection = collect($data['files']);
                $product = $this->associateFiles($product, $collection);
                $product = $this->deleteFilesIfNeeded($product, $collection);
            } elseif ($product->files->count() > 0) {
                $product = $this->deleteFiles($product);
            }

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function delete(int $id): bool
    {
        $product = Product::inShop($this->shopId)->findOrFail($id);

        return $product->delete();
    }

    protected function setTaxId(Product $product, int $taxId): Product
    {
        $taxService = new TaxService($this->shopId);
        $product->tax_id = $taxService->read($taxId)->getKey();

        return $product;
    }

    protected function attachBarcodes(Product $product, array $barcodes): Product
    {
        $barcodeService = new BarcodeService($this->shopId);
        $barcodeIds = [];

        foreach ($barcodes as $barcode) {
            $barcodeIds[] = $barcodeService->updateOrCreate($barcode)?->getKey();
        }

        $product->barcodes()->sync($barcodeIds);

        return $product;
    }

    protected function detachBarcodes(Product $product): Product
    {
        $product->barcodes()->detach();

        return $product;
    }

    protected function associateProductAttributes(Product &$product, Collection $productAttributes): Product
    {
        $productAttributeService = new ProductAttributeService($this->shopId);
        $productAttributes->each(fn ($productAttributeData) => $productAttributeService->updateOrCreate($product, $productAttributeData));

        return $product;
    }

    protected function deleteProductAttributesIfNeeded(Product $product, Collection $collection): Product
    {
        return $this->deleteProductAttributes($product, $collection);
    }

    protected function deleteProductAttributes(Product $product, ?Collection $collection = null): Product
    {
        $productAttributeService = new ProductAttributeService($this->shopId);

        if (! is_null($collection)) {
            $product->productAttributes->each(function ($model) use ($collection, $productAttributeService) {
                $result = $collection->where('id', $model->getKey())->count();

                if (! $result) {
                    $productAttributeService->delete($model->getKey());
                }

                return true;
            });
        } else {
            $product->productAttributes->each(fn ($model) => $productAttributeService->delete($model->getKey()));
        }

        return $product;
    }

    protected function associateSpecialPrices(Product $product, Collection $specialPrices): Product
    {
        $specialPriceService = ProductSpecialPriceService::factory($this->shopId);
        $specialPrices->each(fn ($specialPriceData) => $specialPriceService->updateOrCreate($product, $specialPriceData));

        return $product;
    }

    protected function deleteSpecialPricesIfNeeded(Product $product, Collection $collection): Product
    {
        return $this->deleteSpecialPrices($product, $collection);
    }

    protected function deleteSpecialPrices(Product $product, ?Collection $collection = null): Product
    {
        $specialPriceService = ProductSpecialPriceService::factory($this->shopId);

        if (! is_null($collection)) {
            $product->specialPrices->each(function ($model) use ($collection, $specialPriceService) {
                $result = $collection->where('id', $model->getKey())->count();

                if (! $result) {
                    $specialPriceService->delete($model->getKey());
                }

                return true;
            });
        } else {
            $product->specialPrices->each(fn ($model) => $specialPriceService->delete($model->getKey()));
        }

        return $product;
    }

    protected function associateImages(Product $product, Collection $images): Product
    {
        $productImageService = new ProductImageService($this->shopId);
        $images->each(fn ($imageData) => $productImageService->updateOrCreate($product, $imageData));

        return $product;
    }

    protected function deleteImagesIfNeeded(Product $product, Collection $collection): Product
    {
        return $this->deleteImages($product, $collection);
    }

    protected function deleteImages(Product $product, ?Collection $collection = null): Product
    {
        $productImageService = new ProductImageService($this->shopId);

        if (! is_null($collection)) {
            $product->images->each(function ($model) use ($collection, $productImageService) {
                $result = $collection->where('id', $model->getKey())->count();

                if (! $result) {
                    $productImageService->delete($model->getKey());
                }

                return true;
            });
        } else {
            $product->images->each(fn ($model) => $productImageService->delete($model->getKey()));
        }

        return $product;
    }

    protected function associateFiles(Product $product, Collection $files): Product
    {
        $productFileService = ProductFileService::factory($this->shopId);

        $files->each(fn ($data) => $productFileService->updateOrCreate($product, $data));

        return $product;
    }

    protected function deleteFilesIfNeeded(Product $product, Collection $collection): Product
    {
        return $this->deleteFiles($product, $collection);
    }

    protected function deleteFiles(Product $product, ?Collection $collection = null): Product
    {
        $productImageService = ProductFileService::factory($this->shopId);

        if (! is_null($collection)) {
            $product->files->each(function ($model) use ($collection, $productImageService) {
                $result = $collection->where('id', $model->getKey())->count();

                if (! $result) {
                    $productImageService->delete($model->getKey());
                }

                return true;
            });
        } else {
            $product->files->each(fn ($model) => $productImageService->delete($model->getKey()));
        }

        return $product;
    }
}
