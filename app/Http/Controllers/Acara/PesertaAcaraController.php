<?php

namespace App\Http\Controllers\Acara;

use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Models\Acara\PesertaAcara;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class PesertaAcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $search = $request->search;

        $query = PesertaAcara::with(['warga', 'acara']);

        if ($bulan) {
            $bulan = $this->convertBulanToNumber($request->bulan);
            if ($bulan) {
                $query->whereHas('acara', function ($q) use ($bulan) {
                    $q->whereMonth('tanggal_mulai', $bulan);
                });
            }
        }

        // Filter tahun
        if ($tahun) {
            $query->whereHas('acara', function ($q) use ($request) {
                $q->whereYear('tanggal_mulai', $request->tahun);
            });
        }

        if ($search) {
            $query->whereHas('warga', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%');
            });
        }

        $peserta = $query->get();

        return response()->json($peserta);
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


    public function index()
    {
        $peserta = PesertaAcara::with(['acara', 'warga'])->get();
        return response()->json([
            'message' => 'Data peserta acara berhasil didapatkan',
            'data' => $peserta,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'acara_id_fk' => 'required|exists:acara,id',
                'warga_id_fk' => 'required|exists:warga,id',
                'status_kehadiran' => 'required|string|in:hadir,tidak_hadir,belum_konfirmasi',
            ]);

            // Cek jika kombinasi acara dan warga sudah terdaftar
            $alreadyExists = PesertaAcara::where('acara_id_fk', $validated['acara_id_fk'])
                ->where('warga_id_fk', $validated['warga_id_fk'])
                ->exists();

            if ($alreadyExists) {
                return response()->json([
                    'message' => 'Warga sudah terdaftar sebagai peserta acara ini.',
                ], 409); // 409 Conflict
            }

            $peserta = PesertaAcara::create($validated);

            return response()->json([
                'message' => 'Data peserta acara berhasil ditambahkan',
                'data' => $peserta,
            ], 201); // 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menyimpan ke database',
                'errors' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $peserta = PesertaAcara::with(['acara', 'warga'])->findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Data berita tidak ditemukan'], 404);
        }
        return response()->json([
            'message' => 'Data acara berhasil didapatkan',
            'data' => $peserta,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'acara_id_fk' => 'required|exists:acara,id',
                'warga_id_fk' => 'required|exists:warga,id',
                'status_kehadiran' => 'required|string|in:hadir,tidak_hadir,belum_konfirmasi',
            ]);

            $peserta = PesertaAcara::findOrFail($id)->update($validated);

            return response()->json([
                'message' => 'Data peserta acara berhasil diperbarui',
                'data' => $peserta,
            ], 201); // 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Gagal menyimpan ke database',
                'errors' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $peserta = PesertaAcara::findOrFail($id);
            $peserta->delete();

            return response()->json([
                'message' => 'Data peserta acara berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Data peserta acara gagal dihapus',
                'error'   => $th->getMessage(),
            ], 404);
        }
    }
}
