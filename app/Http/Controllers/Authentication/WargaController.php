<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\Kurban\Kurban;
use App\Models\Warga;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $warga = Warga::all();
            return response()->json([
                'message' => 'Data warga berhasil diambil',
                'data' => $warga,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data warga gagal diambil',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_lengkap' => 'required|string',
                'kontak' => 'required|string',
                'alamat' => 'required|string',
            ]);

            $warga = Warga::create($validated);
            return response()->json([
                'message' => 'Data warga berhasil ditambah',
                'data' => $warga
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data warga gagal ditambahkan',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $warga = Warga::findOrFail($id);
            return response()->json([
                'message' => 'Data warga berhasil ditemukan',
                'data' => $warga
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data warga tidak ditemukan',
                'error' => $th->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'nama_lengkap' => 'required|string',
                'kontak' => 'required|string',
                'alamat' => 'required|string',
            ]);
            $warga = Warga::update($validated);
            return response()->json([
                'message' => 'Data warga berhasil diubah',
                'data' => $warga
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data berita tidak ditemukan',
                'error' => $th->getMessage(),
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $warga = Warga::findOrFail($id)->delete();
            return response()->json([
                'message' => 'Data warga berhasil dihapus',
                'data' => $warga
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Data warga gagal dihapus",
                'error' => $th->getMessage(),
            ], 404);
        }
    }
}
