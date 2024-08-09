<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = Products::with('category')->get();

        $result = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'nama_kategori' => $product->category->nama_kategori,
                'nama' => $product->nama,
                'kode' => $product->kode,
                'qty' => $product->qty,
                'foto' => $product->foto,
            ];
        });

        return response()->json($result);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Products::with('category')->findOrFail($id);

        $result = [
            'id' => $product->id,
            'nama_kategori' => $product->category->nama_kategori,
            'nama' => $product->nama,
            'kode' => $product->kode,
            'qty' => $product->qty,
            'foto' => $product->foto,
        ];

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|integer',
            'nama' => 'required|string|max:255',
            'qty' => 'required|integer',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('app/public/product');
            $image->move($destinationPath, $name);

            $foto = $name;
        } else {
            $foto = null;
        }

        $product = Products::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'qty' => $request->qty,
            'foto' => $foto,
            'category_id' => $request->category_id,
        ]);

        return response()->json($product, 201);
    }


    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'kode' => 'required|integer',
            'nama' => 'required|string|max:255',
            'qty' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Temukan produk berdasarkan ID
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Hapus foto lama jika ada foto baru yang diupload
        if ($request->hasFile('foto')) {
            if ($product->foto) {
                $oldImagePath = storage_path('app/public/product/' . $product->foto);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('foto');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('app/public/product');
            $image->move($destinationPath, $name);

            $foto = $name;
        } else {
            $foto = $product->foto; // Jika tidak ada gambar baru, gunakan gambar lama
        }

        // Update data produk
        $product->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'qty' => $request->qty,
            'foto' => $foto,
            'category_id' => $request->category_id,
        ]);

        return response()->json($product);
    }


    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        // Temukan produk berdasarkan ID
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Hapus foto dari penyimpanan jika ada
        if ($product->foto) {
            $imagePath = storage_path('app/public/product/' . $product->foto);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Hapus produk dari database
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
