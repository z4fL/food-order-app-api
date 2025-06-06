<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Produk::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string',
                'harga' => 'required|integer',
                'kategori' => 'required|in:makanan,minuman',
                'gambar' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $upload = Cloudinary::upload($request->file('gambar')->getRealPath());

        $produk = Produk::create([
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'kategori' => $validated['kategori'],
            'gambar'    => $upload->getSecurePath(),   // URL image
            'public_id' => $upload->getPublicId(),     // Buat hapus/edit
        ]);

        return response()->json($produk, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk not found'], 404);
        }
        return response()->json($produk);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk not found'], 404);
        }

        try {
            $validated = $request->validate([
                'nama' => 'sometimes|required|string',
                'harga' => 'sometimes|required|integer',
                'kategori' => 'sometimes|required|in:makanan,minuman',
                'gambar' => 'sometimes|image|mimes:jpeg,png,jpg|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        if ($request->hasFile('gambar')) {
            if (!empty($produk->public_id)) {
                Cloudinary::destroy($produk->public_id);
            }

            $upload = Cloudinary::upload($request->file('gambar')->getRealPath());

            $validated['gambar'] = $upload->getSecurePath();
            $validated['public_id'] = $upload->getPublicId();
        }

        $produk->update($validated);

        return response()->json($produk);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk not found'], 404);
        }

        Cloudinary::destroy($produk->public_id);
        $produk->delete();

        return response()->json(['message' => 'Produk deleted']);
    }
}
