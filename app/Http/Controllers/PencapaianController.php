<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pencapaian;
use App\Models\Kakitangan;
use App\Models\Peringkatsumbangan;

class PencapaianController extends Controller
{
    public function create(Request $request, $id_kakitangan)
    {
        $kakitangan = Kakitangan::findOrFail($id_kakitangan);
        $peringkat_list = Peringkatsumbangan::orderBy('peringkat', 'asc')->get();

        $pencapaian_list = Pencapaian::with('peringkatSumbangan')
            ->where('id_kakitangan', $id_kakitangan)
            ->orderBy('tarikhpencapaian', 'asc')
            ->get();

        return view('kakitangan.pencapaian_tambah', compact('kakitangan', 'peringkat_list', 'pencapaian_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kakitangan' => 'required|integer',
            'pencapaian' => 'required|string',
            'peringkat' => 'required|integer',
            'tarikhpencapaian' => 'required|date',
        ]);

        Pencapaian::create([
            'id_kakitangan' => $request->id_kakitangan,
            'pencapaian' => $request->pencapaian,
            'peringkat' => $request->peringkat,
            'tarikhpencapaian' => $request->tarikhpencapaian,
            'tarikhkemaskini' => now(),
        ]);

        return redirect()->route('pencapaian.create', ['id' => $request->id_kakitangan])
            ->with('success', 'Maklumat Pencapaian berjaya ditambah.');
    }
}
