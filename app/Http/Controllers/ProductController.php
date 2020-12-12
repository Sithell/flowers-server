<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class
ProductController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 8);
        $sort_by = $request->input('sort_by', 'id');
        $reverse = $request->input('reverse', 'no');
        $lowest_price = $request->input('lowest_price', 0);
        $query = null;
        if ($request->has('highest_price')) {
            $query = Product::where([
                ['price', '>=', $lowest_price],
                ['price', '<=', $request->input('highest_price')]
            ]);
        } else {
            $query = Product::where('price', '>=', $lowest_price);
        }
        if ($reverse == 'yes') {
            $query->orderByDesc($sort_by);
        } else {
            $query->orderBy($sort_by);
        }
        if ($request->has('size')) {
            $query->where('size', '=', $request->input('size'));
        }
        return $this->jsonResponse($query->simplePaginate($per_page));
    }

    public function show(Request $request)
    {
        $id = $request->query('id');
        return $this->jsonResponse(Product::where('id', '=', $id)->first());
    }
}
