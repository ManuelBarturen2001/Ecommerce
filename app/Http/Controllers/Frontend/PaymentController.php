<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaypalSetting;
use App\Models\Product;
use App\Models\IziPaySettings;
use App\Models\RazorpaySetting;
use App\Models\StripeSetting;
use App\Models\Transaction;
use App\Models\CulqiSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Charge;
use Stripe\Stripe;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        if(!Session::has('address')){
            return redirect()->route('user.checkout');
        }
        return view('frontend.pages.payment');
    }

    public function paymentSuccess($invoice_id)
    {
        $order = Order::where('invocie_id', $invoice_id)
                      ->where('user_id', Auth::id())
                      ->first();
        if (!$invoice_id) {
            toastr()->error('No se encontró información de la orden.', 'Error');
            return redirect()->route('user.profile');
        }
        if (!$order) {
            toastr()->error('No se encontró la orden o no tienes acceso.', 'Acceso denegado');
            return redirect()->route('user.payment');
        }

        return view('frontend.pages.payment-success', compact('order'));
    }

    public function createIziPaySession(Request $request)
    {
        try {
            $iziPaySettings = IziPaySettings::first();
            
            if (!$iziPaySettings || !$iziPaySettings->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'IziPay no está configurado o habilitado'
                ], 400);
            }

            $amount = getFinalPayableAmount() * 100; // IziPay usa centavos
            $currency = $iziPaySettings->currency_name;
            $orderId = 'ORDER_' . time() . '_' . rand(1000, 9999);

            // URL base según el modo
            $baseUrl = $iziPaySettings->mode === 'live' 
                ? 'https://api.micuentaweb.pe' 
                : 'https://api.micuentaweb.pe'; // Usar la misma URL para sandbox

            // Datos para crear la sesión de pago
            $paymentData = [
                'amount' => $amount,
                'currency' => $currency,
                'orderId' => $orderId,
                'customer' => [
                    'email' => Auth::user()->email,
                    'reference' => Auth::user()->id
                ],
                'metadata' => [
                    'user_id' => Auth::user()->id,
                    'cart_total' => getCartTotal(),
                    'shipping_fee' => getShppingFee(),
                    'discount' => getCartDiscount()
                ]
            ];

            // Crear la sesión de pago usando la API REST de IziPay
            $response = Http::withBasicAuth($iziPaySettings->shop_id, $iziPaySettings->private_key)
                ->post($baseUrl . '/api-payment/V4/Charge/CreatePayment', $paymentData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Guardar el ID de sesión en la sesión para referencia posterior
                Session::put('izipay_session_id', $responseData['answer']['formToken']);
                Session::put('izipay_order_id', $orderId);

                return response()->json([
                    'success' => true,
                    'formToken' => $responseData['answer']['formToken'],
                    'publicKey' => $iziPaySettings->public_key,
                    'shopId' => $iziPaySettings->shop_id
                ]);
            } else {
                Log::error('Error al crear sesión IziPay: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la sesión de pago'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error en createIziPaySession: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function handleIziPayReturn(Request $request)
    {
        try {
            $iziPaySettings = IziPaySettings::first();
            
            // Verificar la firma HMAC
            $krHash = $request->get('kr-hash');
            $krHashKey = $request->get('kr-hash-key');
            $krAnswer = $request->get('kr-answer');

            // Verificar la integridad de los datos
            $calculatedHash = hash_hmac('sha256', $krAnswer, $iziPaySettings->hmac_sha256_key);
            
            if (!hash_equals($calculatedHash, $krHash)) {
                Log::error('Hash IziPay inválido');
                toastr()->error('Error en la verificación del pago', 'Error');
                return redirect()->route('user.payment');
            }

            // Decodificar la respuesta
            $paymentData = json_decode($krAnswer, true);
            
            if ($paymentData['orderStatus'] === 'PAID') {
                // Pago exitoso
                $transactionId = $paymentData['uuid'];
                $paidAmount = $paymentData['orderDetails']['orderTotalAmount'] / 100; // Convertir de centavos
                
                // Crear la orden
                $order = $this->storeOrder(
                    'IziPay',
                    'paid',
                    $transactionId,
                    $paidAmount,
                    $iziPaySettings->currency_name
                );

                // Limpiar el carrito y sesiones
                \Cart::destroy();
                Session::forget(['address', 'shipping_method', 'coupon', 'izipay_session_id', 'izipay_order_id']);

                toastr()->success('Pago procesado exitosamente!', 'Éxito');
                return redirect()->route('user.payment.success', $order->invocie_id);
                
            } else {
                // Pago fallido
                Log::warning('Pago IziPay fallido: ' . json_encode($paymentData));
                toastr()->error('El pago no pudo ser procesado', 'Error de pago');
                return redirect()->route('user.payment');
            }

        } catch (\Exception $e) {
            Log::error('Error en handleIziPayReturn: ' . $e->getMessage());
            toastr()->error('Error al procesar el pago', 'Error');
            return redirect()->route('user.payment');
        }
    }

    public function storeOrder($paymentMethod, $paymentStatus, $transactionId, $paidAmount, $paidCurrencyName)
    {
        $setting = GeneralSetting::first();
        $address = Session::get('address');
        $shippingMethod = Session::get('shipping_method');

        $order = new Order();
        $order->invocie_id = 'US'.rand(1, 999999);
        $order->user_id = Auth::user()->id;
        $order->sub_total = getCartTotal();
        $order->amount =  getFinalPayableAmount();
        $order->currency_name = $setting->currency_name;
        $order->currency_icon = $setting->currency_icon;
        $order->product_qty = \Cart::content()->count();
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;
        $order->order_address = json_encode($address);
        $order->shpping_method = json_encode($shippingMethod);
        $order->coupon = json_encode(Session::get('coupon'));
        $order->order_status = 'pending';
        
        // Guardar información de pickup en tienda
        if (isset($shippingMethod['is_pickup']) && $shippingMethod['is_pickup']) {
            $order->is_store_pickup = 1;
            if (isset($address['store_pickup_id'])) {
                $order->store_pickup_id = $address['store_pickup_id'];
            }
        } else {
            $order->is_store_pickup = 0;
        }
        
        $order->save();

        // store order products
        foreach(\Cart::content() as $item){
            $product = Product::find($item->id);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->vendor_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->variants = json_encode($item->options->variants);
            $orderProduct->variant_total = $item->options->variants_total;
            $orderProduct->unit_price = $item->price;
            $orderProduct->qty = $item->qty;
            $orderProduct->save();

            // update product quantity
            $updatedQty = ($product->qty - $item->qty);
            $product->qty = $updatedQty;
            $product->save();
        }

        // store transaction details
        $transaction = new Transaction();
        $transaction->order_id = $order->id;
        $transaction->transaction_id = $transactionId;
        $transaction->payment_method = $paymentMethod;
        $transaction->amount = getFinalPayableAmount();
        $transaction->amount_real_currency = $paidAmount;
        $transaction->amount_real_currency_name = $paidCurrencyName;
        $transaction->save();

        return $order;
    }

    public function clearSession()
    {
        \Cart::destroy();
        Session::forget('address');
        Session::forget('shipping_method');
        Session::forget('coupon');
    }

    public function culqiPayment(Request $request)
    {
        try {
            // Validar el token de Culqi
            $request->validate([
                'culqi_token' => 'required|string',
            ]);

            // Verificar configuración de Culqi
            $culqiSetting = CulqiSettings::first();
            
            if (!$culqiSetting) {
                Log::error('Culqi: No se encontró configuración en la base de datos');
                return response()->json(['error' => 'Configuración de Culqi no encontrada'], 400);
            }
            
            if ($culqiSetting->status != 1) {
                Log::error('Culqi: Servicio desactivado en configuración');
                return response()->json(['error' => 'Culqi está desactivado'], 400);
            }

            // Verificar claves
            if (empty($culqiSetting->public_key) || empty($culqiSetting->secret_key)) {
                Log::error('Culqi: Claves no configuradas');
                return response()->json(['error' => 'Claves de Culqi no configuradas'], 400);
            }

            // Verificar sesión de dirección
            $address = Session::get('address');
            if (!$address || !isset($address['email'])) {
                Log::error('Culqi: No se encontró información de dirección en la sesión');
                return response()->json(['error' => 'Información de dirección no válida'], 400);
            }

            // Configurar Culqi
            $secretKey = $culqiSetting->secret_key;
            $environment = $culqiSetting->mode === 'live' 
                ? 'https://api.culqi.com' 
                : 'https://apisandbox.culqi.com';
            
            // Obtener el monto final
            $finalAmount = getFinalPayableAmount();
            if (!$finalAmount || $finalAmount <= 0) {
                Log::error('Culqi: Monto inválido - ' . $finalAmount);
                return response()->json(['error' => 'Monto de pago inválido'], 400);
            }

            // Crear cargo en Culqi
            $charge_data = [
                'amount' => (int)($finalAmount * 100), // Culqi maneja centavos
                'currency_code' => $culqiSetting->currency_name,
                'email' => $address['email'],
                'source_id' => $request->culqi_token,
                'description' => 'Compra en ' . config('app.name', 'Tienda Online'),
                'capture' => true,
                'metadata' => [
                    'order_id' => 'ORDER_' . time(),
                    'customer_name' => $address['name'] ?? 'Cliente'
                ]
            ];

            Log::info('Culqi: Enviando datos de cargo', [
                'amount' => $charge_data['amount'],
                'currency' => $charge_data['currency_code'],
                'email' => $charge_data['email']
            ]);

            $charge = $this->createCulqiCharge($charge_data, $secretKey, $environment);

            if (!$charge) {
                Log::error('Culqi: No se recibió respuesta del API');
                return response()->json(['error' => 'Error de comunicación con Culqi'], 500);
            }

            Log::info('Culqi: Respuesta recibida', $charge);

            // Verificar si el pago fue exitoso
            if (isset($charge['id']) && isset($charge['outcome']) && $charge['outcome']['type'] === 'venta_exitosa') {
                // Crear orden
                $order = $this->storeOrder(
                    'Culqi',
                    'completed',
                    $charge['id'],
                    $finalAmount,
                    $culqiSetting->currency_name
                );
                
                // Guardar invoice_id en sesión para la página de éxito
                Session::put('last_order_invoice', $order->invocie_id);
                
                // Limpiar carrito y sesión
                $this->clearSession();
                
                return response()->json([
                    'status' => 'success',
                    'redirect_url' => route('user.payment.success', ['invoice_id' => $order->invocie_id]),
                    'message' => 'Pago procesado exitosamente'
                ]);
                
            } else {
                $errorMessage = 'Error al procesar el pago';
                
                if (isset($charge['user_message'])) {
                    $errorMessage = $charge['user_message'];
                } elseif (isset($charge['merchant_message'])) {
                    $errorMessage = $charge['merchant_message'];
                } elseif (isset($charge['outcome']['type'])) {
                    $errorMessage = 'Pago rechazado: ' . $charge['outcome']['type'];
                }
                
                Log::error('Culqi: Pago no exitoso', $charge);
                
                return response()->json([
                    'error' => $errorMessage
                ], 400);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Culqi: Error de validación', $e->errors());
            return response()->json(['error' => 'Datos de pago inválidos'], 400);
        } catch (\Exception $e) {
            Log::error('Culqi: Error general - ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /** Create charge in Culqi */
    private function createCulqiCharge($data, $secretKey, $environment)
    {
        $url = $environment . '/v2/charges';
        
        Log::info('Culqi: Enviando request a ' . $url);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // Aumentar timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $secretKey,
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        
        curl_close($curl);
        
        if ($curlError) {
            Log::error('Culqi: Error de cURL - ' . $curlError);
            return false;
        }
        
        Log::info('Culqi: Respuesta HTTP ' . $httpCode . ' - ' . $response);
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200 && $httpCode !== 201) {
            Log::error('Culqi: Error HTTP ' . $httpCode, ['response' => $response]);
            return $result; // Devolver el resultado para manejar el error específico
        }
        
        return $result;
    }


    public function paypalConfig()
    {
        $paypalSetting = PaypalSetting::first();
        $config = [
            'mode'    => $paypalSetting->mode === 1 ? 'live' : 'sandbox',
            'sandbox' => [
                'client_id'         => $paypalSetting->client_id,
                'client_secret'     => $paypalSetting->secret_key,
                'app_id'            => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id'         => $paypalSetting->client_id,
                'client_secret'     => $paypalSetting->secret_key,
                'app_id'            => '',
            ],

            'payment_action' => 'Sale',
            'currency'       => $paypalSetting->currency_name,
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   =>  true,
        ];
        return $config;
    }

    /** Paypal redirect */
    public function payWithPaypal()
    {
        $config = $this->paypalConfig();
        $paypalSetting = PaypalSetting::first();

        $provider = new PayPalClient($config);
        $provider->getAccessToken();


        // calculate payable amount depending on currency rate
        $total = getFinalPayableAmount();
        $payableAmount = round($total*$paypalSetting->currency_rate, 2);


        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('user.paypal.success'),
                "cancel_url" => route('user.paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $config['currency'],
                        "value" => $payableAmount
                    ]
                ]
            ]
        ]);

        if(isset($response['id']) && $response['id'] != null){
            foreach($response['links'] as $link){
                if($link['rel'] === 'approve'){
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('user.paypal.cancel');
        }

    }

    public function paypalSuccess(Request $request)
    {
        $config = $this->paypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            // calculate payable amount depending on currency rate
            $paypalSetting = PaypalSetting::first();
            $total = getFinalPayableAmount();
            $paidAmount = round($total*$paypalSetting->currency_rate, 2);

            $this->storeOrder('paypal', 1, $response['id'], $paidAmount, $paypalSetting->currency_name);

            // clear session
            $this->clearSession();

            return redirect()->route('user.payment.success');
        }

        return redirect()->route('user.paypal.cancel');
    }

    public function paypalCancel()
    {
        toastr('Someting went wrong try agin later!', 'error', 'Error');
        return redirect()->route('user.payment');
    }


    /** Stripe Payment */

    public function payWithStripe(Request $request)
    {
        $stripeSetting = StripeSetting::first();
        $total = getFinalPayableAmount();
        $payableAmount = round($total * $stripeSetting->currency_rate, 2);

        Stripe::setApiKey($stripeSetting->secret_key);
        $response = Charge::create([
            "amount" => $payableAmount * 100,
            "currency" => $stripeSetting->currency_name,
            "source" => $request->stripe_token,
            "description" => "product purchase!"
        ]);

        if ($response->status === 'succeeded') {
            // 🔥 CORREGIDO: guardamos el resultado de storeOrder()
            $order = $this->storeOrder('stripe', 1, $response->id, $payableAmount, $stripeSetting->currency_name);

            Session::put('payment_success', true);
            Session::put('order_id', $order->invocie_id); // ✅ GUARDAMOS EN LA SESIÓN

            $this->clearSessionButKeepPaymentSuccess();

            return redirect()->route('user.payment.success', ['invoice_id' => $order->invocie_id]);
        }
    }

    public function clearSessionButKeepPaymentSuccess()
{
    \Cart::destroy();
    Session::forget('address');
    Session::forget('shipping_method');
    Session::forget('coupon');
    // Mantiene 'payment_success'
}

    /** Razorpay payment */
    public function payWithRazorPay(Request $request)
    {
       $razorPaySetting = RazorpaySetting::first();
       $api = new Api($razorPaySetting->razorpay_key, $razorPaySetting->razorpay_secret_key);

       // amount calculation
       $total = getFinalPayableAmount();
       $payableAmount = round($total * $razorPaySetting->currency_rate, 2);
       $payableAmountInPaisa = $payableAmount * 100;

       if($request->has('razorpay_payment_id') && $request->filled('razorpay_payment_id')){
            try{
                $response = $api->payment->fetch($request->razorpay_payment_id)
                    ->capture(['amount' => $payableAmountInPaisa]);
            }catch(\Exception $e){
                toastr($e->getMessage(), 'error', 'Error');
                return redirect()->back();
            }


            if($response['status'] == 'captured'){
                $this->storeOrder('razorpay', 1, $response['id'], $payableAmount, $razorPaySetting->currency_name);
                // clear session
                $this->clearSession();

                return redirect()->route('user.payment.success');
            }

       }
    }

    /** pay with cod */
    public function payWithCod(Request $request)
    {
        $codPaySetting = CodSetting::first();
        $setting = GeneralSetting::first();
        if($codPaySetting->status == 0){
            return redirect()->back();
        }

        // amount calculation
       $total = getFinalPayableAmount();
       $payableAmount = round($total, 2);


        $this->storeOrder('COD', 0, \Str::random(10), $payableAmount, $setting->currency_name);
        // clear session
        $this->clearSession();

        return redirect()->route('user.payment.success');
            

    }

}
