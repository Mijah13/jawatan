<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IsyiharHartum;
use App\Models\JenisIsytihar;
use App\Models\Kakitangan;
use Illuminate\Support\Facades\DB;

class HartaController extends Controller
{
    public function create(Request $request, $id_kakitangan)
    {

        $kakitangan = Kakitangan::findOrFail($id_kakitangan);
        $jenis_list = JenisIsytihar::orderBy('jenis', 'asc')->get();

        $harta_list = IsyiharHartum::with('jenisIsytihar')
            ->where('id_kakitangan', $id_kakitangan)
            ->orderBy('tarikhisytihar', 'desc')
            ->get();

        return view('kakitangan.isytihar_tambah', compact('kakitangan', 'jenis_list', 'harta_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kakitangan' => 'required|integer',
            'jenis' => 'required|integer',
            'tarikhisytihar' => 'required|date',
            'no_rujukan' => 'required|string',
        ]);

        IsyiharHartum::create([
            'id_kakitangan' => $request->id_kakitangan,
            'jenis' => $request->jenis,
            'tarikhisytihar' => $request->tarikhisytihar,
            'no_rujukan' => $request->no_rujukan,
            'tarikhkemaskini' => now(),
        ]);

        return redirect()->route('harta.create', ['id' => $request->id_kakitangan])
            ->with('success', 'Maklumat harta berjaya ditambah.');
    }

    public function edit($id)
    {
        $harta = IsyiharHartum::findOrFail($id);
        $kakitangan = Kakitangan::findOrFail($harta->id_kakitangan);
        $jenis_list = JenisIsytihar::orderBy('jenis', 'asc')->get();

        return view('kakitangan.isytihar_edit', compact('harta', 'jenis_list', 'kakitangan'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kakitangan' => 'required|integer',
            'jenis' => 'required|integer',
            'tarikhisytihar' => 'required|date',
            'no_rujukan' => 'required|string',
        ]);

        IsyiharHartum::where('id', $id)->update([
            'id_kakitangan' => $request->id_kakitangan,
            'jenis' => $request->jenis,
            'tarikhisytihar' => $request->tarikhisytihar,
            'no_rujukan' => $request->no_rujukan,
            'tarikhkemaskini' => now(),
        ]);

        return redirect()->route('harta.create', ['id' => $request->id_kakitangan])
            ->with('success', 'Maklumat harta berjaya dikemaskini.');
    }

    public function destroy($id)
    {
        $harta = IsyiharHartum::findOrFail($id);
        $id_kakitangan = $harta->id_kakitangan;

        $harta->delete();

        return redirect()->route('harta.create', ['id' => $id_kakitangan])
            ->with('success', 'Maklumat harta berjaya dihapus.');
    }
}
