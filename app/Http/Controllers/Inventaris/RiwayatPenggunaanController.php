<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\RiwayatPenggunaan;
use Illuminate\Http\Request;

class RiwayatPenggunaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all riwayat penggunaan from the database
        // Assuming you have a model named RiwayatPenggunaan
        $riwayatPenggunaan = RiwayatPenggunaan::with('barang')->get();
        return response()->json([
            'message' => 'Data riwayat penggunaan berhasil didapatkan',
            'data' => $riwayatPenggunaan,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'barang_id_fk' => 'required|exists:barang,id',
                'tanggal_pemijaman' => 'required|date',
                'deskripsi_penggunaan' => 'required|string|max:255',
                'tanggal_pengembalian' => 'nullable|date',
            ]);

            $riwayatPenggunaan = RiwayatPenggunaan::create($validated);
            return response()->json([
                'message' => 'Data riwayat penggunaan berhasil ditambahkan',
                'data' => $riwayatPenggunaan,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data riwayat penggunaan',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data riwayat penggunaan',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menambahkan data riwayat penggunaan',
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
            $riwayatPenggunaan = RiwayatPenggunaan::with('barang')->findOrFail($id);
            return response()->json([
                'message' => 'Data riwayat penggunaan berhasil didapatkan',
                'data' => $riwayatPenggunaan,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data riwayat penggunaan tidak ditemukan',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mendapatkan data riwayat penggunaan',
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
            $riwayatPenggunaan = RiwayatPenggunaan::findOrFail($id);
            $validated = $request->validate([
                'barang_id_fk' => 'required|exists:barang,id',
                'tanggal_pemijaman' => 'required|date',
                'deskripsi_penggunaan' => 'required|string|max:255',
                'tanggal_pengembalian' => 'nullable|date',
            ]);

            $riwayatPenggunaan->update($validated);
            return response()->json([
                'message' => 'Data riwayat penggunaan berhasil diperbarui',
                'data' => $riwayatPenggunaan,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data riwayat penggunaan',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data riwayat penggunaan tidak ditemukan',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal memperbarui data riwayat penggunaan',
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
            $riwayatPenggunaan = RiwayatPenggunaan::findOrFail($id);
            $riwayatPenggunaan->delete();
            return response()->json([
                'message' => 'Data riwayat penggunaan berhasil dihapus',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data riwayat penggunaan tidak ditemukan',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menghapus data riwayat penggunaan',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
