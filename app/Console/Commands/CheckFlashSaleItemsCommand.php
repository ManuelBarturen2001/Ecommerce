<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Carbon\Carbon;

class CheckFlashSaleItemsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flash-sale:check-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update status of flash sale items';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking Flash Sale items status...');
        $today = Carbon::today();
        $flashSale = FlashSale::first();
        
        if (!$flashSale) {
            $this->info('No Flash Sale found');
            return Command::SUCCESS;
        }
        
        // Obtener TODOS los productos en Flash Sale (activos e inactivos)
        $flashSaleItems = FlashSaleItem::where('flash_sale_id', $flashSale->id)
            ->get();
            
        $deactivatedCount = 0;
        $activatedCount = 0;
        
        foreach ($flashSaleItems as $item) {
            $product = Product::find($item->product_id);
            $startDate = Carbon::parse($product->offer_start_date)->startOfDay();
            $endDate = Carbon::parse($product->offer_end_date)->endOfDay();
            if (!$product) {
                // Si el producto ya no existe, desactivar en Flash Sale
                if ($item->status == 1) {
                    $item->status = 0;
                    $item->show_at_home = 0;
                    $item->save();
                    $deactivatedCount++;
                    $this->info("Deactivated product #N/A from Flash Sale: product no longer exists");
                }
                continue;
            }
            
            // Calcular el descuento actual
            $discount = 0;
            if ($product->price > 0 && $product->offer_price) {
                $discount = (($product->price - $product->offer_price) / $product->price) * 100;
            }
            
            // Verificar si el producto cumple con TODAS las condiciones para estar activo
            $isEligible = true;
            $reason = '';
            
            // 1. Producto debe tener descuento válido (mayor o igual a 49%)
            if ($discount < 49) {
                $isEligible = false;
                $reason = 'discount below 49%';
            }
            
            // 2. La oferta del producto no debe haber terminado
            if ($endDate < $today) {
                $isEligible = false;
                $reason = 'offer ended';
            }
            
            // 3. El producto debe estar activo
            if ($product->status != 1 || $product->is_approved != 1) {
                $isEligible = false;
                $reason = 'product inactive';
            }
            
            // 4. La oferta del producto debe haber comenzado
            if ($startDate > $today) {
                $isEligible = false;
                $reason = 'offer not started yet';
            }
            
            // 5. El Flash Sale no debe haber terminado
            if ($endDate < $today) {
                $isEligible = false;
                $reason = 'flash sale ended';
            }
            
            // Actualizar estado según la elegibilidad
            if (!$isEligible && $item->status == 1) {
                // Desactivar si no es elegible y está activado
                $item->status = 0;
                $item->show_at_home = 0;
                $item->save();
                $deactivatedCount++;
                $this->info("Deactivated product #{$product->id} from Flash Sale: {$reason}");
            } else if ($isEligible && $item->status == 0) {
                // Activar si es elegible y está desactivado
                $item->status = 1;
                $item->show_at_home = 1;
                $item->save();
                $activatedCount++;
                $this->info("Activated product #{$product->id} in Flash Sale: now meets all criteria");
            }
        }
        
        $this->info("Finished checking. Deactivated {$deactivatedCount} and activated {$activatedCount} products in Flash Sale.");
        return Command::SUCCESS;
    }
}

