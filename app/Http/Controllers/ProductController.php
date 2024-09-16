<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('products.index', compact('categories'));
    }
    public function getData(DataTables $dataTables)
    {
        $products = Product::query();
        return $dataTables->eloquent($products)
            ->addColumn('action', function ($product) {
                return '
                    <button class="btn btn-warning btn-sm edit-btn" data-id="' . $product->id . '">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="' . $product->id . '">Delete</button>
                ';
            })
            ->toJson();
    }
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->except('_token'));
        $product->categories()->sync($request->input('categories'));
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $manager = new ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );
                $compressedImage = $manager->read($image)->encode(new \Intervention\Image\Encoders\AutoEncoder(quality: 70));
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $compressedImage->save(storage_path('app/public/' . $filename));
                $product->images()->create([
                    'image' => $filename,
                    'product_id' => $product->id
                ]);
            }
        }
        return response()->json(1);
    }


    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->except('_token'));
        $product->categories()->sync($request->input('categories'));
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $manager = new ImageManager(
                    new \Intervention\Image\Drivers\Gd\Driver()
                );
                $compressedImage = $manager->read($image)->encode(new \Intervention\Image\Encoders\AutoEncoder(quality: 70));
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $compressedImage->save(storage_path('app/public/' . $filename));
                $product->images()->create([
                    'image' => $filename,
                    'product_id' => $product->id
                ]);
            }
        }
        return response()->json(1);
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
        return response()->json(1);
    }
    public function show($id)
    {
        $product = Product::find($id);
        $categories = $product->categories;
        return response()->json(compact('product', 'categories'));
    }
}
