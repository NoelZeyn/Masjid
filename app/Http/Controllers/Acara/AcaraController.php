<?php

namespace App\Http\Controllers\Acara;

use App\Http\Controllers\Controller;
use App\Models\Acara\Acara;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acaras = Acara::all();
        return response()->json([
            'message' => 'Semua data acara berhasil didapatkan',
            'data' => $acaras,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_acara' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'lokasi' => 'required|string',
                'waktu' => 'required|string|regex:/\d{2}:\d{2} WIB/',
                'status' => 'required|string|in:direncanakan,berjalan,selesai,dibatalkan'
            ]);

            $acaras = Acara::create($$validated);
            return response()->json([
                'message' => 'Data acara berhasil dimasukkan',
                'data' => $acaras,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data berita gagal ditambahkan'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $acaras = Acara::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data berita tidak ditemukan'], 404);
        }
        return response()->json([
            'message' => 'Data acara berhasil didapatkan',
            'data' => $acaras,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'nama_acara' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'lokasi' => 'required|string',
                'waktu' => 'required|string|regex:/\d{2}:\d{2} WIB/',
                'status' => 'required|string|in:direncanakan,berjalan,selesai,dibatalkan'
            ]);

            $acaras = Acara::find($id);
            if (!$acaras) {
                return response()->json(['message' => 'Data acara tidak ditemukan'], 404);
            }

            $acaras->update($validated);
            return response()->json([
                'message' => 'Data acara berhasil dimasukkan',
                'data' => $acaras,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengupdate data berita',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $acaras = Acara::findOrFail($id)->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data berita tidak ditemukan'], 404);
        }
        return response()->json(['message' => 'Data Acara berhasil dihapus', 'data' => $acaras], 200);
    }
}
