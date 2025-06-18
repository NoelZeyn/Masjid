<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all barang from the database
        $barang = Barang::with('supplier', 'transaksi')->get();
        return response()->json([
            'message' => 'Data barang berhasil didapatkan',
            'data' => $barang,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'nama_barang' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:500',
                'harga' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'merk' => 'nullable|string|max:100',
                'kategori' => 'nullable|string|max:100',
                'kondisi' => 'nullable|string|max:50',
                'keterangan' => 'nullable|string|max:500',
            ]);

            $barang = Barang::create($validated);
            return response()->json([
                'message' => 'Data barang berhasil ditambahkan',
                'data' => $barang,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data barang',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data barang',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menambahkan data barang',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $barang = Barang::with(['supplier', 'transaksi'])->findOrFail($id);
            return response()->json([
                'message' => 'Data barang berhasil didapatkan',
                'data' => $barang,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data barang tidak ditemukan',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mendapatkan data barang',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $barang = Barang::with(['supplier', 'transaksi'])->findOrFail($id);
            $validated = $request->validate([
                'supplier_id' => 'sometimes|exists:suppliers,id',
                'nama_barang' => 'sometimes|string|max:255',
                'deskripsi' => 'nullable|string|max:500',
                'harga' => 'sometimes|numeric|min:0',
                'stock' => 'sometimes|integer|min:0',
                'merk' => 'nullable|string|max:100',
                'kategori' => 'nullable|string|max:100',
                'kondisi' => 'nullable|string|max:50',
                'keterangan' => 'nullable|string|max:500',
            ]);

            $barang->update($validated);
            return response()->json([
                'message' => 'Data barang berhasil diperbarui',
                'data' => $barang,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data barang',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data barang',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal memperbarui data barang',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();
            return response()->json([
                'message' => 'Data barang berhasil dihapus',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data barang tidak ditemukan',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menghapus data barang',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
