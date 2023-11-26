<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

// import facade Storage
use Illuminate\Support\Facades\Storage;

use Auth;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'ensureUserRole'])->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // mengambil data dari model Product
        $products = Product::latest()->paginate(5);

        // cek jika user login dan memiliki role sebagai admin
        if (Auth::check() && Auth::user()->is_admin) {
            return view('admin.products.index', [
                'products' => $products
            ]);
        }

        // menampilkan products.index beserta data products
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi data
        $this->validate($request, [
            'name' => 'required',
            'sku' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // menyimpan gambar
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $image->hashName(),
        ]);

        return redirect()->route('products.index')->with(['success' => 'Product has been inserted!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', ['product' =>$product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // validasi data
        $this->validate($request, [
            'name' => 'required',
            'sku' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // cek jika ada gambar yang di upload pada form
        if ($request->hasFile('image')) {

            // menyimpan gambar baru
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            // menghapus gambar yang lama
            Storage::delete('public/products/'.$product->image);

            // mengupdate data product beserta gambar yang baru
            $product->update([
                'name' => $request->name,
                'sku' => $request->sku,
                'price' => $request->price,
                'description' => $request->description,
                'image' => $image->hashName()
            ]);

        } else {
            // mengupdate data tanpa gambar baru
            $product->update([
                'name' => $request->name,
                'sku' => $request->sku,
                'price' => $request->price,
                'description' => $request->description
            ]);
        }

        return redirect()->route('products.index')->with(['success' => 'Product has been updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // menghapus file gambar
        Storage::delete('public/products/'. $product->image);

        // menghapus data product di database
        $product->delete();

        return redirect()->route('products.index')->with(['success' => 'Product has been deleted!']);
    }
}
