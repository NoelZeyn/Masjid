<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\DataDiri;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function showProfile($id)
    {
        try {
            //check di Model Admin
            $admin = Admin::with('dataDiri')->findOrFail($id);

            // Generate URL foto_profil jika ada
            $fotoProfilUrl = $admin->foto_profil ? asset('storage/app/public/' . $admin->foto_profil) : null;

            return response()->json([
                'status' => 'success',
                'message' => 'Data profil pengguna berhasil diambil.',
                'data' => [
                    'nama_lengkap' => $admin->nama_lengkap,
                    'posisi' => $admin->posisi,
                    'foto_profil' => $fotoProfilUrl,
                    'email' => $admin->email,
                    'tugas' => $admin->tugas,
                    'kontak' => optional($admin->dataDiri)->kontak,
                    'alamat' => optional($admin->dataDiri)->alamat,
                    'instagram' => optional($admin->dataDiri)->instagram,
                    'facebook' => optional($admin->dataDiri)->facebook,
                    'tiktok' => optional($admin->dataDiri)->tiktok,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data profil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        try {
            $admin = Admin::with('dataDiri')->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admin,email,' . $admin->id,
                'password' => 'nullable|string|min:6|confirmed',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

                // Validasi untuk data_diri
                'kontak' => 'nullable|string|max:255',
                'alamat' => 'nullable|string|max:255',
                'instagram' => 'nullable|string|max:255',
                'facebook' => 'nullable|string|max:255',
                'tiktok' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update Admin
            $admin->update($request->only('nama_lengkap', 'email'));

            // Update password jika diberikan dan beda dari sebelumnya
            if ($request->filled('password') && !Hash::check($request->password, $admin->password)) {
                $admin->update(['password' => Hash::make($request->password)]);
            }

            // Update foto profil jika diunggah
            if ($request->hasFile('foto_profil')) {
                if ($admin->foto_profil && Storage::exists($admin->foto_profil)) {
                    Storage::delete($admin->foto_profil);
                }
                $path = $request->file('foto_profil')->store('Photo-Profile');
                if (!$path) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal mengupload foto profil.'
                    ], 500);
                }
                $admin->update(['foto_profil' => $path]);
            }

            // Update data_diri
            $dataDiriData = $request->only(['kontak', 'alamat', 'instagram', 'facebook', 'tiktok']);

            if ($admin->dataDiri) {
                $admin->dataDiri->update($dataDiriData);
            } else {
                $admin->dataDiri()->create($dataDiriData);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui.',
                'data' => [
                    'nama_lengkap' => $admin->nama_lengkap,
                    'email' => $admin->email,
                    'posisi' => $admin->posisi,
                    'tugas' => $admin->tugas,
                    'foto_profil' => $admin->foto_profil ? asset('storage/' . $admin->foto_profil) : null,
                    'kontak' => optional($admin->dataDiri)->kontak,
                    'alamat' => optional($admin->dataDiri)->alamat,
                    'instagram' => optional($admin->dataDiri)->instagram,
                    'facebook' => optional($admin->dataDiri)->facebook,
                    'tiktok' => optional($admin->dataDiri)->tiktok,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $admin = Admin::findOrFail(Auth::id());

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            if (!Hash::check($request->current_password, $admin->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password saat ini salah'
                ], 400);
            }
            if (!Hash::check($request->password, $admin->password)) {
                $admin->update(['password' => Hash::make($request->password)]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Password berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage()
            ], 500);
        }
    }
}
