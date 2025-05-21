<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\FlashSaleItem;
use App\Models\FlashSale;
use Carbon\Carbon;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $today = Carbon::today();
        $flashSale = FlashSale::first();
        
        if (!$flashSale) {
            return; // No hay Flash Sale configurado
        }
        
        // Verificar si el estado del producto fue cambiado
        if ($product->isDirty('status')) {
            // Verificar si el producto se desactivó
            if ($product->status == 0) {
                // Desactivar productos en Flash Sale relacionados
                FlashSaleItem::where('product_id', $product->id)
                    ->where('flash_sale_id', $flashSale->id)
                    ->update([
                        'status' => 0,
                        'show_at_home' => 0
                    ]);
                return; // No continuar con el resto de la lógica si el producto está desactivado
            } 
            // Si el producto se activó, evaluar si debe estar en Flash Sale
            else if ($product->status == 1) {
                // Continuar con la evaluación normal de elegibilidad
                // No hay return aquí para que siga el flujo normal
            }
        }
        
        // Continuar con la evaluación para productos que fueron activados o actualizados
        $startDate = Carbon::parse($product->offer_start_date)->startOfDay();
        $endDate = Carbon::parse($product->offer_end_date)->endOfDay();
        
        // Verificar si el producto ya está en el Flash Sale
        $flashSaleItem = FlashSaleItem::where('product_id', $product->id)
            ->where('flash_sale_id', $flashSale->id)
            ->first();
        
        // Si no está en Flash Sale y no cumple los criterios, no hay nada que hacer
        if (!$flashSaleItem) {
            return;
        }
        
        // Calcular el descuento actual
        $discount = 0;
        if ($product->price > 0 && $product->offer_price) {
            $discount = (($product->price - $product->offer_price) / $product->price) * 100;
        }
        
        // Verificar si el producto cumple con todas las condiciones para estar en Flash Sale
        $isEligible = 
            $discount >= 49 &&
            $startDate <= $today &&
            $endDate >= $today &&
            $product->status == 1 && 
            $product->is_approved == 1 &&
            !empty($product->offer_price) &&
            Carbon::parse($flashSale->end_date)->endOfDay() >= $today;
        
        // Actualizar estado del producto en Flash Sale según su elegibilidad
        if ($isEligible && $flashSaleItem->status == 0) {
            // Reactivar producto en Flash Sale si cumple todos los criterios
            $flashSaleItem->status = 1;
            $flashSaleItem->show_at_home = 1;
            $flashSaleItem->save();
        } else if (!$isEligible && $flashSaleItem->status == 1) {
            // Desactivar si ya no cumple los criterios
            $flashSaleItem->status = 0;
            $flashSaleItem->show_at_home = 0;
            $flashSaleItem->save();
        }
    }

    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        // Si quieres añadir productos automáticamente al Flash Sale al crearlos
        // puedes implementar la lógica aquí
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        // Eliminar registros relacionados en Flash Sale cuando se elimina un producto
        FlashSaleItem::where('product_id', $product->id)->delete();
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        // Cuando se restaura un producto (soft delete), evaluar si debería estar en Flash Sale
        $this->checkFlashSaleEligibility($product);
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        // En caso de que uses SoftDeletes y fuerces la eliminación de un producto
        FlashSaleItem::where('product_id', $product->id)->delete();
    }

    /**
     * Utility method to check if a product is eligible for Flash Sale
     * and update its status accordingly
     * 
     * @param \App\Models\Product $product
     * @return void
     */
    private function checkFlashSaleEligibility(Product $product)
    {
        $today = Carbon::today();
        $flashSale = FlashSale::first();
        
        if (!$flashSale) {
            return;
        }
        
        // Calcular el descuento actual
        $discount = 0;
        if ($product->price > 0 && $product->offer_price) {
            $discount = (($product->price - $product->offer_price) / $product->price) * 100;
        }
        
        $startDate = Carbon::parse($product->offer_start_date)->startOfDay();
        $endDate = Carbon::parse($product->offer_end_date)->endOfDay();
        
        // Verificar si el producto cumple con todas las condiciones
        $isEligible = 
            $discount >= 49 &&
            $startDate <= $today &&
            $endDate >= $today &&
            $product->status == 1 && 
            $product->is_approved == 1 &&
            !empty($product->offer_price) &&
            Carbon::parse($flashSale->end_date)->endOfDay() >= $today;
        
        // Buscar si el producto ya está en Flash Sale
        $flashSaleItem = FlashSaleItem::where('product_id', $product->id)
            ->where('flash_sale_id', $flashSale->id)
            ->first();
        
        if ($flashSaleItem) {
            // Actualizar estado según elegibilidad
            if ($isEligible && $flashSaleItem->status == 0) {
                $flashSaleItem->status = 1;
                $flashSaleItem->show_at_home = 1;
                $flashSaleItem->save();
            } else if (!$isEligible && $flashSaleItem->status == 1) {
                $flashSaleItem->status = 0;
                $flashSaleItem->show_at_home = 0;
                $flashSaleItem->save();
            }
        }
    }
}
    /*
    public function updated(Product $product)
    {
        $today = Carbon::today();
        $flashSale = FlashSale::first();
        $startDate = Carbon::parse($product->offer_start_date)->startOfDay();
        $endDate = Carbon::parse($product->offer_end_date)->endOfDay();

        if (!$flashSale) {
            return; // No hay Flash Sale configurado
        }
        
        // Verificar si el producto ya está en el Flash Sale
        $flashSaleItem = FlashSaleItem::where('product_id', $product->id)
            ->where('flash_sale_id', $flashSale->id)
            ->first();
        
        // Calcular el descuento actual
        $discount = 0;
        if ($product->price > 0 && $product->offer_price) {
            $discount = (($product->price - $product->offer_price) / $product->price) * 100;
        }
        
        // Verificar si el producto cumple con todas las condiciones para estar en Flash Sale
        $isEligible = 
            $discount >= 49 &&
            $startDate < $today &&
            $endDate > $today &&
            $product->status == 1 && 
            $product->is_approved == 1 &&
            !empty($product->offer_price) &&
            $endDate > $today;
        
        // Si el producto ya está en Flash Sale
        if ($flashSaleItem) {
            // Actualizar su estado basado en elegibilidad
            if ($isEligible) {
                // Si está desactivado pero ahora es elegible, activarlo
                if ($flashSaleItem->status == 0) {
                    $flashSaleItem->status = 1;
                    $flashSaleItem->show_at_home = 1;
                    $flashSaleItem->save();
                }
            } else {
                // Si está activado pero ya no es elegible, desactivarlo
                if ($flashSaleItem->status == 1) {
                    $flashSaleItem->status = 0;
                    $flashSaleItem->show_at_home = 0;
                    $flashSaleItem->save();
                }
            }
        } 
        // Si el producto no está en Flash Sale pero es elegible, podríamos añadirlo automáticamente
        // Nota: Esta parte es opcional, depende de tu lógica de negocio
        
        else if ($isEligible) {
            // Añadir automáticamente al Flash Sale
            $newFlashSaleItem = new FlashSaleItem();
            $newFlashSaleItem->product_id = $product->id;
            $newFlashSaleItem->flash_sale_id = $flashSale->id;
            $newFlashSaleItem->show_at_home = 0; // Por defecto no mostrar en home
            $newFlashSaleItem->status = 1;
            $newFlashSaleItem->save();
        }
        
    }*/

