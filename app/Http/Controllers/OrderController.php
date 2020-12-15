<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $order = new Order();

        $order->user_id = $request->user()->id;
        $order->payment_method = $request->input('payment_method');
        $order->change = $request->input('change');
        $order->address = $request->input('address');
        $order->receiver_name = $request->input('receiver_name');
        $order->contact_phone = $request->input('contact_phone');
        $order->postcard = $request->input('postcard');
        if ($order->postcard && !$request->has('postcard_text')) {
            return $this->jsonResponse([], 400, "Не указан текст открытки");
        }
        $order->postcard = $request->input('postcard', "");

        $order->deliver_by = $request->input('deliver_by');

        $order->save();

        $price = 0;
        $order_items = $request->input('items');
        foreach ($order_items as $order_item) {
            $product = Product::where('id', '=', $order_item['product_id'])->first();
            if ($product->left_in_stock < $order_item['quantity']) {
                return $this->jsonResponse([], 403, "Sorry, ".$product->name." is out of stock");
            }
            $product->left_in_stock -= $order_item['quantity'];
            $product->times_bought++;
            $product->save();

            $price += $product->price;

            $item = new OrderItem();
            $item->product_id = $order_item['product_id'];
            $item->order_id = $order->id;
            $item->quantity = $order_item['quantity'];
            $item->save();
        }
        $order->price = $price;
        $order->save();

        return $this->jsonResponse($order, 201);
    }

    public function show(Request $request) {
        $user_id = $request->user()->id;
        return $this->jsonResponse(Order::where('user_id', '=', $user_id)->get());
    }
}
