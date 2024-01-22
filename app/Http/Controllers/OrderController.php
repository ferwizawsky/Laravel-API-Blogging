<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Component;
use App\Models\Order;
use App\Models\Product;
use App\Services\Midtrans\CreateSnapTokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{


    public function index(Request $request)
    {
        $data = Order::where("code", "like", "%" . $request->name . "%")->paginate($request->limit ?? 10);
        return OrderResource::collection($data);
    }

    public function orders(Request $request)
    {
        $request->validate([
            "slug" => "required",
            "product" => "required",
            "product_id" => "required",
        ]);

        // if ($tmp) return response()->json([
        //     "message" => "Link already taken"
        // ], 404); 
        $product = Product::find($request->product_id);
        if (!$product) return response()->json([
            "message" => "Product Not Found"
        ], 404);

        $code = $this->generateCode(5);
        $order = Order::create([
            "code" => $code,
            "total_price" => $product->price,
            "message" => $request->message ?? null,
            "payment_status" => 1,
            "snap_token" => null,
            "user_id" => $request->user ?? 1,
        ]);

        $this->generateOrderMidtrans($order);
        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            "price" => "required"
        ]);
        $tmp = Order::create([
            "name" => $request->name,
            "detail" => $request->detail ?? null,
            "price" => $request->price ?? 1000
        ]);
        // return new OrderResource($tmp);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            "name" => "required",
            "price" => "required"
        ]);
        $tmp1 = Order::find($id);
        if (!$tmp1) return response()->json(["message" => "Not Found"], 404);
        $tmp1->update([
            "name" => $request->name,
            "detail" => $request->detail ?? null,
            "price" => $request->price ?? 1000
        ]);
        $tmp = Order::find($id);
        // return new OrderResource($tmp);
    }

    public function show(Order $order)
    {
        $snapToken = $order->snap_token;
        if (is_null($snapToken)) {
            $midtrans = new CreateSnapTokenService($order);
            $snapToken = $midtrans->getSnapToken();
            $order->snap_token = $snapToken;
            $order->save();
        }
        return view('orders.show', compact('order', 'snapToken'));
    }

    public function generateOrderMidtrans(Order $order)
    {
        $snapToken = $order->snap_token;
        if (is_null($snapToken)) {
            $midtrans = new CreateSnapTokenService($order);
            $snapToken = $midtrans->getSnapToken();
            $order->snap_token = $snapToken;
            $order->save();
        }
    }

    public function generateCode($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        $code = Carbon::now()->timestamp;
        return $code . $randomString;
    }
}
