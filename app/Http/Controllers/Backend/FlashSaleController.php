<?php
namespace App\Http\Controllers\Backend;

use App\DataTables\FlashSaleItemDataTable;
use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    public function index(FlashSaleItemDataTable $dataTable)
    {
        $today = Carbon::today();
        $flashSaleDate = FlashSale::first();
    
        // Verificar y actualizar el estado de los productos en Flash Sale
        $this->updateFlashSaleItemsStatus();
    
        // Filtramos los productos que siguen activos y son válidos para el Flash Sale
        $products = Product::where('is_approved', 1)
            ->where('status', 1)
            ->whereNotNull('offer_price')
            ->whereRaw('(price - offer_price) / price * 100 >= 50')
            ->whereDate('offer_start_date', '<=', $today)
            ->whereDate('offer_end_date', '>=', $today) 
            ->orderBy('id', 'DESC')
            ->get();
    
        return $dataTable->render('admin.flash-sale.index', compact('flashSaleDate', 'products'));
    }    

    /**
     * Actualiza el estado de los productos en Flash Sale basado en:
     * 1. Si la oferta del producto ha terminado
     * 2. Si el producto ya no tiene un descuento válido
     * 3. Si el Flash Sale ha terminado
     * 
     * También activa productos que ahora cumplen con las condiciones
     */
    public function updateFlashSaleItemsStatus()
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $flashSale = FlashSale::first();
        
        if (!$flashSale) {
            return;
        }

        if (Carbon::parse($flashSale->end_date)->lt($today)) {
            FlashSaleItem::where('flash_sale_id', $flashSale->id)
                ->update([
                    'status' => 0,
                    'show_at_home' => 0
                ]);
            return;
        }
        
        // Obtener TODOS los productos en Flash Sale (activos e inactivos)
        $flashSaleItems = FlashSaleItem::where('flash_sale_id', $flashSale->id)
            ->get();
            
        foreach ($flashSaleItems as $item) {
            $product = Product::find($item->product_id);
            
            if (!$product) {
                // Si el producto ya no existe, desactivar en Flash Sale
                $item->status = 0;
                $item->show_at_home = 0;
                $item->save();
                continue;
            }
            
            // Calcular el descuento actual
            $discount = 0;
            if ($product->price > 0 && $product->offer_price) {
                $discount = (($product->price - $product->offer_price) / $product->price) * 100;
            }
            
            // Verificar si el producto cumple con TODAS las condiciones para estar activo
            $isEligible = 
                $discount >= 49 &&
                Carbon::parse($product->offer_start_date)->startOfDay() <= $today &&    
                Carbon::parse($product->offer_end_date)->endOfDay() >= $today &&
                $product->status == 1 && 
                $product->is_approved == 1 &&
                !empty($product->offer_price) &&
                Carbon::parse($flashSale->end_date)->endOfDay() >= $today;
                
            // Actualizar el estado según la elegibilidad
            if ($isEligible && $item->status == 0) {
                // Activar si es elegible y está desactivado
                $item->status = 1;
                $item->show_at_home = 1;
                $item->save();
            } else if (!$isEligible && $item->status == 1) {
                // Desactivar si no es elegible y está activado
                $item->status = 0;
                $item->show_at_home = 0;
                $item->save();
            }
        }
    }

    public function update(Request $request)
    {
       $request->validate([
        'end_date' => ['required']
       ]);

       FlashSale::updateOrCreate(
            ['id' => 1],
            ['end_date' => $request->end_date]
       );

       // Actualizar estado de productos después de cambiar la fecha del Flash Sale
       $this->updateFlashSaleItemsStatus();

       toastr('Updated Successfully!', 'success', 'Success');

       return redirect()->back();
    }

    public function addProduct(Request $request)
    {
        $today = Carbon::today();
        
        $request->validate([
            'product' => ['required', 'unique:flash_sale_items,product_id'],
            'show_at_home' => ['required'],
            'status' => ['required'],
        ],[
            'product.unique' => 'The product is already in flash sale!'
        ]);

        $flashSaleDate = FlashSale::first();
        $product = Product::findOrFail($request->product);
        $discount = (($product->price - $product->offer_price) / $product->price) * 100;

        // Aseguramos que las fechas se comparan correctamente a nivel de día
        $startDate = Carbon::parse($product->offer_start_date)->startOfDay();
        $endDate = Carbon::parse($product->offer_end_date)->endOfDay();
        
        if (
            $discount <= 48 ||
            $startDate > $today ||
            $endDate < $today
        ) {
            return redirect()->back()->withErrors(['product' => 'This product does not have a valid active 50%+ discount.']);
        } else if ($discount >= 49){
            $flashSaleItem = new FlashSaleItem();
            $flashSaleItem->product_id = $request->product;
            $flashSaleItem->flash_sale_id = $flashSaleDate->id;
            $flashSaleItem->show_at_home = $request->show_at_home;
            $flashSaleItem->status = $request->status;
            $flashSaleItem->save();
        
            toastr('Product Added Successfully!', 'success', 'Success');
            return redirect()->back();
        }
    }
    public function chageShowAtHomeStatus(Request $request)
    {
        $flashSaleItem = FlashSaleItem::findOrFail($request->id);
        $flashSaleItem->show_at_home = $request->status == 'true' ? 1 : 0;
        $flashSaleItem->save();

        return response(['message' => 'Status has been updated!']);
    }

    public function changeStatus(Request $request)
    {
        $flashSaleItem = FlashSaleItem::findOrFail($request->id);
        $flashSaleItem->status = $request->status == 'true' ? 1 : 0;
        $flashSaleItem->save();

        return response(['message' => 'Status has been updated!']);
    }

    public function destory(string $id)
    {
        $flashSaleItem = FlashSaleItem::findOrFail($id);
        $flashSaleItem->delete();
        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
}
