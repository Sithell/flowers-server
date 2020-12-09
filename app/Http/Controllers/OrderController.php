<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request) {
        $user_id = $request->user()->id;
        $payment_method = $request->input('payment_method');
        $order_items = $request->input('items');

        $order = new Order();
        $order->user_id = $user_id;
        $order->payment_method = $payment_method;
        $price = 0;
        $order->save();
//        foreach ($order_items as $product_id) {
////            $price += Product::where('id', '=', $product_id)->first()->price;
//
//            if (Product::where('id', '=', $product_id)->count() == 0) {
//                return "No product found with such id";
//            }
//            $item = new OrderItem();
//            $item->product_id = $product_id;
//            $item->order_id = $order->getNextId();
//            $item->save();
//        }
        foreach ($order_items as $order_item) {
            $price += Product::where('id', '=', $order_item)->first()->price;
            $item = new OrderItem();
            $item->product_id = $order_item;
            $item->order_id = $order->id;
            $item->save();
        }
        $order->price = $price;
        $order->save();

        return "Created new order".$price." with id=".$order->id;
    }
}
