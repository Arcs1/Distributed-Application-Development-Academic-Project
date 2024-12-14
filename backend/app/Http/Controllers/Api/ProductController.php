<?php

namespace App\Http\Controllers\Api;

use Nette\Utils\Image;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();

        $product->name = $request->name;
        $product->type = $request->type;
        $product->price = $request->price;
        $product->description = $request->description;
        if ($request->hasFile('photo')) {
            Storage::delete('storage/products/' . $product->photo_url);
            $path = $request->photo->store('public/products/');
            $product->photo_url = basename($path);
        }
        $product->save();
        return new ProductResource($product);
    }

    public function find($id)
    {
        return new ProductResource(Product::where('id', $id)->first());
    }

    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function paginate()
    {
        return ProductResource::collection(Product::paginate(10));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return new ProductResource($product);
    }

    public function store(Request $request, Product $product)
    {
        var_dump($request->photo);
        $product->name = $request['name'];
        $product->type = $request['type'];
        $product->description = $request['description'];
        $product->price = $request['price'];

        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $image = request()->file('photo');
        $imageName = $image->getClientOriginalName();
        $imageName = time() . '_' . $imageName;

        $image->move(('storage/products'), $imageName);

        $product->photo_url = $imageName;

        $product->save();
    }
}
