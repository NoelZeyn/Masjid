<?php

namespace App\Http\Controllers\Kurban;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Kurban\Kurban;
use Illuminate\Http\Request;

class KurbanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $kurban = Kurban::all();
            return response()->json([
                'message' => 'Data kurban berhasil diambil',
                'data' => $kurban,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data kurban gagal diambil',
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
                'jenis_hewan' => 'required|string|in:kambing,sapi,domba,unta',
                'jumlah' => 'required|integer',
                'status' => 'required|string|in:belum_disembelih',
                'admin_id' => 'required|integer|exist:admin,id'
            ]);

            $kurban = Kurban::create($validated);
            return response()->json([
                'message' => 'Data kurban berhasil ditambah',
                'data' => $kurban
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data kurban gagal ditambahkan',
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
            $kurban = Kurban::findOrFail($id);
            return response()->json([
                'message' => 'Data kurban berhasil ditemukan',
                'data' => $kurban
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data kurban tidak ditemukan',
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
                'jenis_hewan' => 'required|string|in:kambing,sapi,domba,unta',
                'jumlah' => 'required|integer',
                'status' => 'required|string|in:belum_disembelih',
                'admin_id' => 'required|integer|exist:admin,id'
            ]);
            $kurban = Kurban::update($validated);
            return response()->json([
                'message' => 'Data kurban berhasil diubah',
                'data' => $kurban
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
            $kurban = Kurban::findOrFail($id)->delete();
            return response()->json([
                'message' => 'Data kurban berhasil dihapus',
                'data' => $kurban
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Data kurban gagal dihapus",
                'error' => $th->getMessage(),
            ], 404);
        }
    }
}
