<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::all();
        return view('materi.index', compact('materis'));
    }

    public function create()
    {
        return view('materi.tambahMateri');
    }

    public function store(Request $request)
    {
        // === PERUBAHAN: Tambahkan validasi untuk link_yt ===
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_yt' => 'nullable|url|max:255', // Memastikan ini adalah URL yang valid jika diisi
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('materi_images', 'public');
        }

        // === PERUBAHAN: Tambahkan link_yt ke data yang akan disimpan ===
        Materi::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'gambar' => $gambarPath,
            'link_yt' => $request->link_yt,
        ]);

        return redirect()->route('materi')->with('success', 'Materi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $materi = Materi::findOrFail($id);
        return view('materi.editMateri', compact('materi'));
    }

    public function update(Request $request, $id)
    {
        // === PERUBAHAN: Tambahkan validasi untuk link_yt ===
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_yt' => 'nullable|url|max:255',
        ]);

        $materi = Materi::findOrFail($id);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($materi->gambar) {
                Storage::disk('public')->delete($materi->gambar);
            }
            $gambarPath = $request->file('gambar')->store('materi_images', 'public');
        } else {
            $gambarPath = $materi->gambar;
        }

        // === PERUBAHAN: Tambahkan link_yt ke data yang akan diperbarui ===
        $materi->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'gambar' => $gambarPath,
            'link_yt' => $request->link_yt,
        ]);

        return redirect()->route('materi')->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);

        if ($materi->gambar) {
            Storage::disk('public')->delete($materi->gambar);
        }

        $materi->delete();

        return redirect()->route('materi')->with('success', 'Materi berhasil dihapus');
    }
}
