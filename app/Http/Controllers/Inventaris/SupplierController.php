<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Inventaris\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Supplier::all();
        return response()->json([
            'message' => 'Data supplier berhasil didapatkan',
            'data' => $transaksi,
        ], 200);
    }

    public function searchSupplier(Request $request)
    {
        try {
            $query = Supplier::query();

            if ($request->filled('nama_supplier')) {
                $query->where('nama_supplier', 'like', '%' . $request->nama_supplier . '%');
            }

            if ($request->filled('alamat')) {
                $query->where('alamat', 'like', '%' . $request->alamat . '%');
            }

            if ($request->filled('kontak')) {
                $query->where('kontak', 'like', '%' . $request->kontak . '%');
            }

            if ($request->filled('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }

            $results = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => $results->isEmpty() ? 'Data tidak ditemukan.' : 'Data berhasil dicari.',
                'data' => $results,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mencari data supplier',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_supplier' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'kontak' => 'required|string|max:20',
                'email' => 'required|email|max:255',
            ]);
            $supplier = Supplier::create($validated);
            return response()->json([
                'message' => 'Data supplier berhasil ditambahkan',
                'data' => $supplier,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data supplier',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data supplier',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menambahkan data supplier',
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
            $supplier = Supplier::findOrFail($id);
            return response()->json([
                'message' => 'Data supplier berhasil didapatkan',
                'data' => $supplier,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data supplier tidak ditemukan',
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan data supplier',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal mendapatkan data supplier',
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
            $supplier = Supplier::findOrFail($id);
            $validated = $request->validate([
                'nama_supplier' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'kontak' => 'required|string|max:20',
                'email' => 'required|email|max:255',
            ]);
            $supplier->update($validated);
            return response()->json([
                'message' => 'Data supplier berhasil diperbarui',
                'data' => $supplier,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data supplier tidak ditemukan',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data supplier',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data supplier',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal memperbarui data supplier',
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
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            return response()->json([
                'message' => 'Data supplier berhasil dihapus',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data supplier tidak ditemukan',
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Gagal menghapus data supplier',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Gagal menghapus data supplier',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
