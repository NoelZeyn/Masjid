<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all transactions from the database
        $transaksi = Transaksi::with('admin', 'barang')->get();
        return response()->json([
            'message' => 'Data transaksi berhasil didapatkan',
            'data' => $transaksi,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'admin_id' => 'required|exists:admins,id',
                'tanggal_transaksi' => 'required|date',
                'tipe_transaksi' => 'required|string|max:50',
                'total_harga' => 'required|numeric|min:0',
            ]);

            $transaksi = Transaksi::create($validated);
            return response()->json([
                'message' => 'Data transaksi berhasil ditambahkan',
                'data' => $transaksi,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data transaksi',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data transaksi',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menambahkan data transaksi',
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
            $transaksi = Transaksi::with('admin', 'barang')->findOrFail($id);
            return response()->json([
                'message' => 'Data transaksi berhasil didapatkan',
                'data' => $transaksi,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data transaksi tidak ditemukan',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mendapatkan data transaksi',
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
            $transaksi = Transaksi::findOrFail($id);
            $validated = $request->validate([
                'admin_id' => 'sometimes|exists:admins,id',
                'tanggal_transaksi' => 'sometimes|date',
                'tipe_transaksi' => 'sometimes|string|max:50',
                'total_harga' => 'sometimes|numeric|min:0',
            ]);

            $transaksi->update($validated);
            return response()->json([
                'message' => 'Data transaksi berhasil diperbarui',
                'data' => $transaksi,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data transaksi',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal memperbarui data transaksi',
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
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();
            return response()->json([
                'message' => 'Data transaksi berhasil dihapus',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data transaksi tidak ditemukan',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menghapus data transaksi',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
