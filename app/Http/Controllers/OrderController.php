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
        $order->contact_phone = $request->input('contact_phone');
        $order->deliver_by = $request->input('deliver_by');

        $order->save();

        $price = 0;
        $order_items = $request->input('items');
        foreach ($order_items as $order_item) {
            $product = Product::where('id', '=', $order_item['product_id'])->first();
            if ($product->left_in_stock < $order_item['quantity']) {
                return "Sorry, ".$product->name." is out of stock";
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

        return "Created new order, price: ".$price." id: ".$order->id;
    }
}
