<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ruang = Ruang::latest()->paginate(10);
        return view('ruang.index', compact('ruang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ruang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:ruang,nama',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama ruang wajib diisi.',
            'nama.unique' => 'Nama ruang sudah ada.',
        ]);

        Ruang::create($request->all());

        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ruang $ruang)
    {
        return view('ruang.edit', compact('ruang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ruang $ruang)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:ruang,nama,' . $ruang->id,
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama ruang wajib diisi.',
            'nama.unique' => 'Nama ruang sudah ada.',
        ]);

        $ruang->update($request->all());

        return redirect()->route('ruang.index')->with('success', 'Ruang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruang $ruang)
    {
        $ruang->delete();
        return back()->with('success', 'Ruang berhasil dihapus.');
    }
}
