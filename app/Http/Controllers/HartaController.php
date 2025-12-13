<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Isyiharhartum;
use App\Models\Jenisisytihar;
use App\Models\Kakitangan;
use Illuminate\Support\Facades\DB;

class HartaController extends Controller
{
    public function create(Request $request, $id_kakitangan)
    {

        $kakitangan = Kakitangan::findOrFail($id_kakitangan);
        $jenis_list = Jenisisytihar::orderBy('jenis', 'asc')->get();

        $harta_list = Isyiharhartum::with('jenisIsytihar')
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

        Isyiharhartum::create([
            'id_kakitangan' => $request->id_kakitangan,
            'jenis' => $request->jenis,
            'tarikhisytihar' => $request->tarikhisytihar,
            'no_rujukan' => $request->no_rujukan,
            'tarikhkemaskini' => now(),
        ]);

        return redirect()->route('harta.create', ['id' => $request->id_kakitangan])
            ->with('success', 'Maklumat harta berjaya ditambah.');
    }
}
