<?php

namespace App\Http\Controllers;

use App\Models\ChecklistTemplate;
use App\Models\ChecklistItem;
use App\Models\KategoriSarpras;
use Illuminate\Http\Request;

class ChecklistTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = ChecklistTemplate::with(['kategori', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('checklist.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriSarpras::orderBy('nama')->get();
        return view('checklist.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori_sarpras,id',
            'deskripsi' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
        ], [
            'items.required' => 'Minimal harus ada 1 item checklist.',
            'items.min' => 'Minimal harus ada 1 item checklist.',
            'items.*.nama.required' => 'Nama item checklist wajib diisi.',
        ]);

        $template = ChecklistTemplate::create([
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'is_active' => true,
        ]);

        foreach ($request->items as $index => $item) {
            $template->items()->create([
                'nama' => $item['nama'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'urutan' => $index + 1,
            ]);
        }



        return redirect()->route('checklist.index')
            ->with('success', 'Template checklist berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChecklistTemplate $checklist)
    {
        $kategoris = KategoriSarpras::orderBy('nama')->get();
        $checklist->load('items');
        
        return view('checklist.edit', compact('checklist', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChecklistTemplate $checklist)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori_sarpras,id',
            'deskripsi' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
        ]);

        $checklist->update([
            'nama' => $request->nama,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
        ]);

        // Delete existing items and recreate
        $checklist->items()->delete();
        
        foreach ($request->items as $index => $item) {
            $checklist->items()->create([
                'nama' => $item['nama'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'urutan' => $index + 1,
            ]);
        }



        return redirect()->route('checklist.index')
            ->with('success', 'Template checklist berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChecklistTemplate $checklist)
    {
        $nama = $checklist->nama;
        $checklist->delete();



        return redirect()->route('checklist.index')
            ->with('success', 'Template checklist berhasil dihapus!');
    }

    /**
     * Toggle template active status.
     */
    public function toggleStatus(ChecklistTemplate $checklist)
    {
        $checklist->update(['is_active' => !$checklist->is_active]);

        $status = $checklist->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('checklist.index')
            ->with('success', "Template checklist berhasil {$status}!");
    }
}
