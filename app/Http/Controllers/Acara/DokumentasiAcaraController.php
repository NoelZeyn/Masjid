<?php

namespace App\Http\Controllers\Acara;

use App\Http\Controllers\Controller;
use App\Models\Acara\DokumentasiAcara;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DokumentasiAcaraController extends Controller
{
    /**
     * Menampilkan semua data dokumentasi acara.
     */
    public function index()
    {
        $dokumentasi = DokumentasiAcara::with('acara')->get();

        return response()->json([
            'message' => 'Semua data dokumentasi acara berhasil didapatkan',
            'data'    => $dokumentasi,
        ], 200);
    }

    /**
     * Menyimpan dokumentasi acara baru.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'acara_id_fk' => 'required|exists:acara,id',
                'tipe'        => 'required|in:foto,video,dokumen',
                'file_path'   => 'nullable|string',
                'link'        => 'nullable|url',
                'catatan'     => 'nullable|string',
                'uploaded_at' => 'nullable|date',
            ]);

            // Validasi khusus jika tipe dokumen
            if ($validated['tipe'] === 'dokumen' && empty($validated['file_path']) && empty($validated['link'])) {
                return response()->json([
                    'message' => 'Dokumen harus memiliki file atau link.'
                ], 422);
            }

            $dokumentasi = DokumentasiAcara::create($validated);

            return response()->json([
                'message' => 'Data dokumentasi acara berhasil ditambahkan',
                'data'    => $dokumentasi,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan dokumentasi',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Menampilkan detail dokumentasi acara berdasarkan ID.
     */

    public function searchDokumentasiAcara(Request $request)
    {
        try {
            $query = DokumentasiAcara::with('acara');

            $query->whereHas('acara', function ($q) use ($request) {
                if ($request->filled('nama')) {
                    $q->where('nama_acara', 'like', '%' . $request->nama . '%');
                }

                if ($request->filled('bulan')) {
                    $bulan = $this->convertBulanToNumber($request->bulan);
                    if ($bulan) {
                        $q->whereMonth('tanggal_mulai', $bulan);
                    }
                }

                if ($request->filled('tahun')) {
                    $q->whereYear('tanggal_mulai', $request->tahun);
                }

                if ($request->filled('kategori_id')) {
                    $q->where('kategori_id', $request->kategori_id);
                }
            });

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
    public function show(string $id)
    {
        try {
            $dokumentasi = DokumentasiAcara::with('acara')->findOrFail($id);

            return response()->json([
                'message' => 'Data dokumentasi acara berhasil didapatkan',
                'data'    => $dokumentasi,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data dokumentasi acara tidak ditemukan',
                'error'   => $th->getMessage(),
            ], 404);
        }
    }

    /**
     * Memperbarui data dokumentasi acara.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'acara_id_fk' => 'required|exists:acara,id',
                'tipe'        => 'required|in:foto,video,dokumen',
                'file_path'   => 'nullable|string',
                'link'        => 'nullable|url',
                'catatan'     => 'nullable|string',
                'uploaded_at' => 'nullable|date',
            ]);

            if ($validated['tipe'] === 'dokumen' && empty($validated['file_path']) && empty($validated['link'])) {
                return response()->json([
                    'message' => 'Dokumen harus memiliki file atau link.'
                ], 422);
            }

            $dokumentasi = DokumentasiAcara::findOrFail($id);
            $dokumentasi->update($validated);

            return response()->json([
                'message' => 'Data dokumentasi acara berhasil diperbarui',
                'data'    => $dokumentasi,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui dokumentasi',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Menghapus dokumentasi acara berdasarkan ID.
     */
    public function destroy(string $id)
    {
        try {
            $dokumentasi = DokumentasiAcara::findOrFail($id);
            $dokumentasi->delete();

            return response()->json([
                'message' => 'Data dokumentasi acara berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data dokumentasi acara gagal dihapus',
                'error'   => $th->getMessage(),
            ], 404);
        }
    }
}
