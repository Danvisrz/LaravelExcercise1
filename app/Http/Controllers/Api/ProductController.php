<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // GET: Menampilkan data dengan Search, Filter, dan Pagination
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Fitur Search berdasarkan nama
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter berdasarkan kategori
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Pagination
        $products = $query->latest()->paginate(10);
        return ProductResource::collection($products);
    }

    // POST: Tambah produk dengan File Upload & DB Transaction
    public function store(StoreProductRequest $request)
    {
        try {
            $product = DB::transaction(function () use ($request) {
                $validated = $request->validated();
                
                // Upload Gambar
                if ($request->hasFile('img')) {
                    $validated['img'] = $request->file('img')->store('products', 'public');
                }

                $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
                
                return Product::create($validated);
            });

            return response()->json([
                'message' => 'Product created successfully',
                'data' => new ProductResource($product)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);
        }
    }

    // GET: Detail Produk
    public function show(Product $product)
    {
        $product->load('category');
        return new ProductResource($product);
    }

    // PUT/PATCH: Update Produk
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $updatedProduct = DB::transaction(function () use ($request, $product) {
                $validated = $request->validated();

                if ($request->hasFile('img')) {
                    // Hapus gambar lama jika ada
                    if ($product->img) {
                        Storage::disk('public')->delete($product->img);
                    }
                    $validated['img'] = $request->file('img')->store('products', 'public');
                }

                $product->update($validated);
                return $product;
            });

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => new ProductResource($updatedProduct)
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update product', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE: Hapus Produk
    public function destroy(Product $product)
    {
        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}