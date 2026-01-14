<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apc;
use App\Models\Kakitangan;

class ApcController extends Controller
{
    public function create(Request $request, $id_kakitangan)
    {

        $kakitangan = Kakitangan::findOrFail($id_kakitangan);

        $apc_list = Apc::where('id_kakitangan', $id_kakitangan)
            ->orderBy('tahunterima', 'asc')
            ->get();

        return view('kakitangan.apc_tambah', compact('kakitangan', 'apc_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kakitangan' => 'required|integer',
            'tahunterima' => 'required|integer|digits:4',
        ]);

        // Create date from year (e.g. 2023-01-01) since database expects date
        Apc::create([
            'id_kakitangan' => $request->id_kakitangan,
            'tahunterima' => $request->tahunterima,
            'tarikhkemaskini' => now(),
        ]);

        return redirect()->route('apc.create', ['id' => $request->id_kakitangan])
            ->with('success', 'Maklumat APC berjaya ditambah.');
    }
    public function edit($id)
    {
        $apc = Apc::findOrFail($id);
        $kakitangan = Kakitangan::findOrFail($apc->id_kakitangan);

        return view('kakitangan.apc_edit', compact('apc', 'kakitangan'));
    }
    public function update(Request $request, $id)
    {
        $apc = Apc::findOrFail($id);
        $apc->update($request->all());
        return redirect()->route('apc.create', ['id' => $apc->id_kakitangan])
            ->with('success', 'Maklumat APC berjaya dikemaskini.');
    }
    public function destroy($id)
    {
        $apc = Apc::findOrFail($id);
        $apc->delete();
        return redirect()->route('apc.create', ['id' => $apc->id_kakitangan])
            ->with('success', 'Maklumat APC berjaya dihapus.');
    }
}
