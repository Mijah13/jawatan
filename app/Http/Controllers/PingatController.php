<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pingat;
use App\Models\Kakitangan;

class PingatController extends Controller
{
    public function create(Request $request, $id_kakitangan)
    {

        $kakitangan = Kakitangan::findOrFail($id_kakitangan);

        $pingat_list = Pingat::where('id_kakitangan', $id_kakitangan)
            ->orderBy('tarikhterima', 'desc')
            ->get();

        return view('kakitangan.pingat_tambah', compact('kakitangan', 'pingat_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kakitangan' => 'required|integer',
            'pingat' => 'required|string',
            'tarikhterima' => 'required|date',
        ]);

        Pingat::create([
            'id_kakitangan' => $request->id_kakitangan,
            'pingat' => $request->pingat,
            'tarikhterima' => $request->tarikhterima,
            'kemaskini' => now(),
        ]);


        return redirect()->route('pingat.create', ['id' => $request->id_kakitangan])
            ->with('success', 'Maklumat Pingat berjaya ditambah.');
    }
}
