<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use Illuminate\Http\Request;

class BantuanController extends Controller
{
    public function index()
    {
        $data = Bantuan::all();
        return view('bantuan.index', compact('data'));
    }

    public function create()
    {
        return view('bantuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bantuan' => 'required',
        ]);

        Bantuan::create($request->all());

        return redirect()->route('bantuan.index')
            ->with('success', 'Rekod bantuan berjaya ditambah!');
    }

    public function show($id)
    {
        $item = Bantuan::findOrFail($id);
        return view('bantuan.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Bantuan::findOrFail($id);
        return view('bantuan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Bantuan::findOrFail($id);

        $request->validate([
            'nama_bantuan' => 'required',
        ]);

        $item->update($request->all());

        return redirect()->route('bantuan.index')
            ->with('success', 'Rekod bantuan berjaya dikemaskini!');
    }

    public function destroy($id)
    {
        Bantuan::findOrFail($id)->delete();

        return redirect()->route('bantuan.index')
            ->with('success', 'Rekod bantuan berjaya dipadam!');
    }

    // Help Pages
    public function tentang()
    {
        return view('bantuan.tentang');
    }

    public function manual()
    {
        return view('bantuan.manual');
    }

    public function manualPermohonanSuratPengesahan()
    {
        return view('bantuan.manual_permohonan_surat_pengesahan');
    }

    public function manualPelulusSuratPengesahan()
    {
        return view('bantuan.manual_pelulus_surat_pengesahan');
    }

    public function manualSuratAkuanPerubatan()
    {
        return view('bantuan.manual_surat_akuan_perubatan');
    }

    public function cadangan()
    {
        return view('bantuan.cadangan');
    }
}
