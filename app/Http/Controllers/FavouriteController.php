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
            return $this::jsonResponse([], 403, "Этот товар уже добавлен в избранное");
        }
        $favourite = new Favourite();
        $favourite->user_id = $user_id;
        $favourite->product_id = $product_id;
        $favourite->save();

        $product = Product::where('id', '=', $product_id)->first();
        $product->times_liked++;
        $product->save();

        return $this::jsonResponse($product, 201);
    }

    public function show(Request $request) {
        $user_id = $request->user()->id;
        $result = array();
        $favourites = Favourite::where('user_id', '=', $user_id)->get();
        foreach ($favourites as $favourite) {
            $product = Product::where('id', '=', $favourite->product_id)->first();
            array_push($result, $product);
        }
        return $this::jsonResponse($result);
    }

    public function delete(Request $request) {
        $user_id = $request->user()->id;
        $product_id = $request->input('id');
        $favourites = Favourite::where([['user_id', '=', $user_id], ['product_id', '=', $product_id]]);
        if ($favourites->count() == 0) {
            return $this::jsonResponse([], 404, "No such product in favourites");
        }
        $favourites->delete();
        return $this::jsonResponse(['mess' => "Product $product_id removed from favourites"], 200);
    }
}
