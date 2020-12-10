<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function create(Request $request) {
        $user_id = $request->user()->id;
        $product_id = $request->input('product_id');
        if (Favourite::where([
                ['user_id', '=', $user_id],
                ['product_id', '=', $product_id],
            ])->count() > 0) {
            return "Этот товар уже добавлен в избранное";
        }
        $favourite = new Favourite();
        $favourite->user_id = $user_id;
        $favourite->product_id = $product_id;
        $favourite->save();

        $product = Product::where('id', '=', $product_id)->first();
        $product->times_liked++;
        $product->save();

        return "Товар с id: ".$product_id." добавлен в избранное";
    }

    public function show(Request $request) {
        $user_id = $request->user()->id;
        $result = array();
        $favourites = Favourite::where('user_id', '=', $user_id)->get();
        foreach ($favourites as $favourite) {
            $product = Product::where('id', '=', $favourite->product_id)->first();
            array_push($result, $product);
        }
        return $result;
    }
}
