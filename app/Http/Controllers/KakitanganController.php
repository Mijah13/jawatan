<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kakitangan;
use Illuminate\Support\Facades\DB;

class KakitanganController extends Controller
{
    public function index()
    {
        $id = auth()->user()->id;
        // $rows = Kakitangan::find($id);
        $kakitangan = Kakitangan::with([
            'apc' => fn($q) => $q->orderBy('tahunterima', 'desc'),
            'pencapaian' => fn($q) => $q->orderBy('tarikhpencapaian', 'desc')->with('peringkatSumbangan'),
            'pingat' => fn($q) => $q->orderBy('kemaskini', 'asc'),
            'harta' => fn($q) => $q->orderBy('tarikhisytihar', 'desc')->with('jenisIsytihar'),
            'jawatanRelation',
            'gredRelation',
            'penempatanWaranRelation',
            'penempatanOperasiRelation',
            'unitRelation',
            'kodPenempatanRelation'
        ])->where('id', $id)->firstOrFail();
        return view('kakitangan.index', compact('kakitangan'));
    }

    public function display($id)
    {
        // $rows = Kakitangan::find($id);
        $kakitangan = Kakitangan::with([
            'apc' => fn($q) => $q->orderBy('tahunterima', 'desc'),
            'pencapaian' => fn($q) => $q->orderBy('tarikhpencapaian', 'desc')->with('peringkatSumbangan'),
            'pingat' => fn($q) => $q->orderBy('kemaskini', 'asc'),
            'harta' => fn($q) => $q->orderBy('tarikhisytihar', 'desc')->with('jenisIsytihar'),
            'jawatanRelation',
            'gredRelation',
            'penempatanWaranRelation',
            'penempatanOperasiRelation',
            'unitRelation',
            'kodPenempatanRelation'
        ])->where('id', $id)->firstOrFail();

        return view('kakitangan.display', compact('kakitangan'));
    }

    public function carian(Request $request)
    {
        $cari = $request->input('cari');

        $rows = collect(); // empty by default

        if ($cari) {
            $rows = Kakitangan::where('nama', 'like', "%$cari%")
                ->orWhere('mykad', 'like', "%$cari%")
                ->orderBy('nama')
                ->get();
        }

        return view('kakitangan.carian', compact('rows'));
    }



    public function create()
    {
        $jawatan = DB::table('jawatan')
            ->select('id', DB::raw("CONCAT(kod, ' - ', jawatan) AS jwt"))
            ->orderBy('jawatan', 'asc')->get();

        $gred = DB::table('gred')->orderBy('gred', 'asc')->get();

        $tmpwrn = DB::table('organisasi')
            ->select('id', 'program')
            ->orderBy('program', 'asc')->get();

        $unit = DB::table('unit')
            ->join('organisasi', 'unit.program', '=', 'organisasi.id')
            ->select('unit.id', DB::raw("CONCAT(organisasi.kod, ' - ', unit.unit) AS kodprg"))
            ->orderBy('kodprg', 'asc')->get();

        $operasi = DB::table('organisasi')
            ->select('id', 'program')
            ->orderBy('program', 'asc')->get();

        $kodtmpt = DB::table('penempatan')
            ->select('id', DB::raw("CONCAT(kod, ' - ', jenis) AS penempatan"))
            ->orderBy('kod', 'asc')->get();

        $level = DB::table('levelpengguna')
            ->select('level', 'nama')
            ->orderBy('level', 'desc')->get();

        return view('kakitangan.create', compact(
            'jawatan',
            'gred',
            'tmpwrn',
            'unit',
            'operasi',
            'kodtmpt',
            'level'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mykad' => 'required|string|unique:kakitangan,mykad',
            'nama' => 'required|string',
            'katalaluan' => 'required|string',
            'tarikhlahir' => 'nullable|date',
            'nofailperibadi' => 'nullable|string',
            'jawatan' => 'nullable|integer',
            'gred' => 'nullable|integer',
            'nowaran' => 'nullable|string',
            'penempatanwaran' => 'nullable|integer',
            'penempatanoperasi' => 'nullable|integer',
            'unit' => 'nullable|integer',
            'tarikhlantikanpertama' => 'nullable|date',
            'tarikhlantikansekarang' => 'nullable|date',
            'tarikhpengesahanjawatan' => 'nullable|date',
            'tarikhmemangku' => 'nullable|date',
            'tarikhnaikpangkat' => 'nullable|date',
            'tarikhkeciast' => 'nullable|date',
            'tarikhbertukarkeluar' => 'nullable|date',
            'penempatanbaru' => 'nullable|string',
            'hrmiskemaskini' => 'nullable|string',
            'kodpenempatan' => 'nullable|integer',
            'level' => 'nullable|integer',
            'emel' => 'nullable|string',
        ]);

        Kakitangan::create($request->all());
        return redirect()->route('kakitangan.index')->with('success', 'Kakitangan berjaya ditambah.');
    }

    public function edit($id)
    {
        // MAIN KAKITANGAN DATA + RELATIONS
        $row = Kakitangan::with([
            'apc' => fn($q) => $q->orderBy('tahunterima', 'desc'),
            'pencapaian' => fn($q) => $q->orderBy('tarikhpencapaian', 'desc')->with('peringkatSumbangan'),
            'pingat' => fn($q) => $q->orderBy('kemaskini', 'asc'),
            'harta' => fn($q) => $q->orderBy('tarikhisytihar', 'desc')->with('jenisIsytihar')
        ])->where('id', $id)->firstOrFail();

        // EXTRA DROPDOWNS (old system)
        $jawatan = DB::table('jawatan')
            ->select('id', DB::raw("CONCAT(kod, ' - ', jawatan) AS jwt"))
            ->orderBy('jawatan', 'asc')->get();

        $gred = DB::table('gred')->orderBy('gred', 'asc')->get();

        $tmpwrn = DB::table('organisasi')
            ->select('id', 'program')
            ->orderBy('program', 'asc')->get();

        $unit = DB::table('unit')
            ->join('organisasi', 'unit.program', '=', 'organisasi.id')
            ->select('unit.id', DB::raw("CONCAT(organisasi.kod, ' - ', unit.unit) AS kodprg"))
            ->orderBy('kodprg', 'asc')->get();

        $operasi = DB::table('organisasi')
            ->select('id', 'program')
            ->orderBy('program', 'asc')->get();

        $kodtmpt = DB::table('penempatan')
            ->select('id', DB::raw("CONCAT(kod, ' - ', jenis) AS penempatan"))
            ->orderBy('kod', 'asc')->get();

        $level = DB::table('levelpengguna')
            ->select('level', 'nama')
            ->orderBy('level', 'desc')->get();

        return view('kakitangan.edit', compact(
            'jawatan',
            'gred',
            'tmpwrn',
            'unit',
            'operasi',
            'kodtmpt',
            'level',
            'row'
        ));
    }


    // public function update(Request $r, $id)
    // {

    //     Kakitangan::find($id)->update($r->all());
    //     return redirect()->route('kakitangan.index');
    // }

    public function update(Request $request, $id)
    {
        // dd($request->all()); // Debugging: Check if request reaches here
        $row = Kakitangan::findOrFail($id);

        // Validate fields (optional but recommended)
        $request->validate([
            'mykad' => 'required|string',
            'nama' => 'required|string',
            'tarikhlahir' => 'nullable|date',
            'nofailperibadi' => 'nullable|string',
            'jawatan' => 'nullable|integer',
            'gred' => 'nullable|integer',
            'nowaran' => 'nullable|string',
            'penempatanwaran' => 'nullable|integer',
            'penempatanoperasi' => 'nullable|integer',
            'unit' => 'nullable|integer',
            'tarikhlantikanpertama' => 'nullable|date',
            'tarikhlantikansekarang' => 'nullable|date',
            'tarikhpengesahanjawatan' => 'nullable|date',
            'tarikhmemangku' => 'nullable|date',
            'tarikhnaikpangkat' => 'nullable|date',
            'tarikhkeciast' => 'nullable|date',
            'tarikhbertukarkeluar' => 'nullable|date',
            'penempatanbaru' => 'nullable|string',
            'hrmiskemaskini' => 'nullable|string',
            'kodpenempatan' => 'nullable|integer',
            'level' => 'nullable|integer',
            'emel' => 'nullable|string',
        ]);

        // Mass assignment
        $row->update([
            'mykad' => $request->mykad,
            'nama' => $request->nama,
            'tarikhlahir' => $request->tarikhlahir,
            'nofailperibadi' => $request->nofailperibadi,
            'jawatan' => $request->jawatan,
            'gred' => $request->gred,
            'nowaran' => $request->nowaran,
            'penempatanwaran' => $request->penempatanwaran,
            'penempatanoperasi' => $request->penempatanoperasi,
            'unit' => $request->unit,
            'tarikhlantikanpertama' => $request->tarikhlantikanpertama,
            'tarikhlantikansekarang' => $request->tarikhlantikansekarang,
            'tarikhpengesahanjawatan' => $request->tarikhpengesahanjawatan,
            'tarikhmemangku' => $request->tarikhmemangku,
            'tarikhnaikpangkat' => $request->tarikhnaikpangkat,
            'tarikhkeciast' => $request->tarikhkeciast,
            'tarikhbertukarkeluar' => $request->tarikhbertukarkeluar,
            'penempatanbaru' => $request->penempatanbaru,
            'hrmiskemaskini' => $request->hrmiskemaskini,
            'kodpenempatan' => $request->kodpenempatan,
            'level' => $request->level,
            'emel' => $request->emel,
        ]);

        return redirect()->route('kakitangan.edit', $row->id)
            ->with('success', 'Maklumat kakitangan berjaya dikemaskini.');
    }

    public function reset($id)
    {
        $row = Kakitangan::find($id);
        $row->katalaluan = '1234';
        $row->save();
        return back();
    }

    public function destroy($id)
    {
        Kakitangan::find($id)->delete();
        return back();
    }
}
