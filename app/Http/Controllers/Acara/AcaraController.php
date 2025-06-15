<?php

namespace App\Http\Controllers\Acara;

use App\Http\Controllers\Controller;
use App\Models\Acara\Acara;
use App\Models\Acara\KategoriAcara;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acaras = Acara::with('kategori')->get();
        return response()->json([
            'message' => 'Semua data acara berhasil didapatkan',
            'data' => $acaras,
        ], 200);
    }

    public function searchAcara(Request $request)
    {
        try {
            $query = Acara::with('kategori');

            if ($request->filled('kategori')) {
                $kategori = $request->kategori;
                $query->whereHas('kategori', function ($q) use ($kategori) {
                    $q->where('nama', 'like', '%' . $kategori . '%');
                });
            }

            if ($request->filled('nama')) {
                $query->where('nama_acara', 'like', '%' . $request->nama . '%');
            }

            if ($request->filled('bulan')) {
                $bulan = $this->convertBulanToNumber($request->bulan);
                if ($bulan) {
                    $query->whereMonth('tanggal_mulai', $bulan);
                }
            }

            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_mulai', $request->tahun);
            }

            $results = $query->get();

            return response()->json([
                'status'  => 'success',
                'message' => $results->isEmpty() ? 'Data tidak ditemukan.' : 'Data berhasil dicari.',
                'data'    => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mencari acara: ' . $e->getMessage()
            ], 500);
        }
    }
    private function convertBulanToNumber($bulan)
    {
        $map = [
            'Januari'   => 1,
            'Februari'  => 2,
            'Maret'     => 3,
            'April'     => 4,
            'Mei'       => 5,
            'Juni'      => 6,
            'Juli'      => 7,
            'Agustus'   => 8,
            'September' => 9,
            'Oktober'   => 10,
            'November'  => 11,
            'Desember'  => 12
        ];

        return $map[$bulan] ?? null;
    }


    public function showKategori()
    {
        $kategori = KategoriAcara::all();
        return response()->json([
            'message' => 'Data kategori berhasil didapatkan',
            'data' => $kategori,
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
                'kategori_id' => 'required|exists:kategori_acara,id',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'lokasi' => 'required|string',
                'waktu' => 'required|string',
                'status' => 'required|string|in:direncanakan,berjalan,selesai,dibatalkan'
            ]);

            $acaras = Acara::create($validated);
            return response()->json([
                'message' => 'Data acara berhasil ditambahkan',
                'data' => $acaras,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data berita gagal ditambahkan',
                'error'=>$th->getMessage()
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $acaras = Acara::with('kategori')->findOrFail($id);
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
                'kategori_id' => 'required|exists:kategori_acara,id',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'lokasi' => 'required|string',
                'waktu' => 'required|string',
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
