<?php

namespace App\Http\Controllers;

use App\Models\Keluarga;
use App\Models\Hubungan;
use Illuminate\Http\Request;

class KeluargaController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;

        $rows = Keluarga::with('hubunganInfo')
            ->where('idkakitangan', $userId)
            ->orderBy('nama')
            ->get();

        return view('kakitangan.keluarga.index', compact('rows'));
    }

    public function create()
    {
        $hubungan = Hubungan::orderBy('hubungan')->get();
        return view('kakitangan.keluarga.create', compact('hubungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'hubungan' => 'required|integer|exists:hubungan,id',
        ]);

        Keluarga::create([
            'idkakitangan' => auth()->user()->id,
            'nama' => $request->nama,
            'hubungan' => $request->hubungan,
        ]);

        return redirect()->route('keluarga.index')
            ->with('success', 'Rekod keluarga berjaya ditambah.');
    }

    public function edit(Keluarga $keluarga)
    {
        $hubungan = Hubungan::orderBy('hubungan')->get();
        return view('kakitangan.keluarga.edit', compact('keluarga', 'hubungan'));
    }

    public function update(Request $request, Keluarga $keluarga)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'hubungan' => 'required|integer|exists:hubungan,id',
        ]);

        $keluarga->update($request->only(['nama', 'hubungan']));

        return redirect()->route('keluarga.index')
            ->with('success', 'Rekod keluarga berjaya dikemaskini.');
    }

    public function destroy(Keluarga $keluarga)
    {
        $keluarga->delete();

        return redirect()->route('keluarga.index')
            ->with('success', 'Rekod keluarga berjaya dipadam.');
    }
}
