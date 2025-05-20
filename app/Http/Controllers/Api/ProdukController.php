<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
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

        // Store the uploaded image with a hashed name in 'public/gambar' directory
        $file = $request->file('gambar');
        $hashedName = $file->hashName('gambar');
        $file->storeAs('images', basename($hashedName), 'public');

        $produk = Produk::create([
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'kategori' => $validated['kategori'],
            'gambar' => $hashedName, // Save the hashed path to the database
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
            $file = $request->file('gambar');
            $hashedName = $file->hashName('gambar');
            $file->storeAs('images', basename($hashedName), 'public');
            $validated['gambar'] = $hashedName;
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

        $produk->delete();

        return response()->json(['message' => 'Produk deleted']);
    }
}
