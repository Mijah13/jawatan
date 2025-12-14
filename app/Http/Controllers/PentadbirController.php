<?php
namespace App\Http\Controllers;

use App\Models\Pentadbir;
use App\Models\PeringkatSumbangan;
use App\Models\Organisasi;
use App\Models\Unit;
use App\Models\JenisIsytihar;
use App\Models\Penempatan;
use App\Models\Jawatan;
use App\Models\Gred;
use App\Models\Perjawatan;
use App\Models\Elaun;
use App\Models\MotoHariPekerja;
use Illuminate\Http\Request;

class PentadbirController extends Controller
{
    public function index()
    {
        $data = Pentadbir::all();
        return view('pentadbir.index', compact('data'));
    }

    public function create()
    {
        return view('pentadbir.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pentadbirs,email',
        ]);

        Pentadbir::create($request->all());

        return redirect()->route('pentadbir.index')
            ->with('success', 'Pentadbir berjaya ditambah!');
    }

    public function show($id)
    {
        $item = Pentadbir::findOrFail($id);
        return view('pentadbir.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Pentadbir::findOrFail($id);
        return view('pentadbir.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Pentadbir::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pentadbirs,email,' . $id,
        ]);

        $item->update($request->all());

        return redirect()->route('pentadbir.index')
            ->with('success', 'Rekod pentadbir berjaya dikemaskini!');
    }

    public function destroy($id)
    {
        Pentadbir::findOrFail($id)->delete();

        return redirect()->route('pentadbir.index')
            ->with('success', 'Pentadbir berjaya dipadam!');
    }

    // ========================================
    // PENETAPAN (SETTINGS) METHODS
    // ========================================

    public function peringkatSumbangan(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'peringkat' => 'required|string|max:50',
            ]);

            PeringkatSumbangan::create([
                'peringkat' => $request->peringkat,
            ]);

            return redirect()->route('pentadbir.peringkat_sumbangan')
                ->with('success', 'Peringkat sumbangan berjaya ditambah!');
        }

        $rows = PeringkatSumbangan::orderBy('peringkat', 'asc')->get();
        return view('pentadbir.peringkat_sumbangan', compact('rows'));
    }

    public function program(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'kod' => 'required|string|max:50',
                'nama' => 'required|string|max:200',
            ]);

            Organisasi::create([
                'kod' => $request->kod,
                'program' => $request->nama,
            ]);

            return redirect()->route('pentadbir.program')
                ->with('success', 'Program berjaya ditambah!');
        }

        $rows = Organisasi::orderBy('program', 'asc')->get();
        return view('pentadbir.program', compact('rows'));
    }

    public function unit(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'kod' => 'required|string|max:50',
                'nama' => 'required|string|max:200',
                'program' => 'required|integer',
            ]);

            Unit::create([
                'kod' => $request->kod,
                'unit' => $request->nama,
                'program' => $request->program,
            ]);

            return redirect()->route('pentadbir.unit')
                ->with('success', 'Unit berjaya ditambah!');
        }


        $rows = Unit::select('unit.unit', 'unit.id', 'organisasi.kod')
            ->join('organisasi', 'organisasi.id', '=', 'unit.program')
            ->orderBy('organisasi.kod', 'asc')
            ->orderBy('unit.unit', 'asc')
            ->get();

        $program_list = Organisasi::orderBy('program', 'asc')->get();

        return view('pentadbir.unit', compact('rows', 'program_list'));
    }

    public function jenisIsytihar(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'jenis' => 'required|string|max:200',
            ]);

            JenisIsytihar::create([
                'jenis' => $request->jenis,
            ]);

            return redirect()->route('pentadbir.jenis_isytihar')
                ->with('success', 'Jenis isytihar berjaya ditambah!');
        }

        $rows = JenisIsytihar::orderBy('jenis', 'asc')->get();
        return view('pentadbir.jenis_isytihar', compact('rows'));
    }

    public function jenisPenempatan(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'kod' => 'required|string|max:10',
                'jenis' => 'required|string|max:200',
            ]);

            Penempatan::create([
                'kod' => $request->kod,
                'jenis' => $request->jenis,
            ]);

            return redirect()->route('pentadbir.jenis_penempatan')
                ->with('success', 'Jenis penempatan berjaya ditambah!');
        }

        $rows = Penempatan::orderBy('kod', 'asc')->get();
        return view('pentadbir.jenis_penempatan', compact('rows'));
    }

    public function jawatan(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'kod' => 'required|string|max:50',
                'jawatan' => 'required|string|max:200',
            ]);

            Jawatan::create([
                'kod' => $request->kod,
                'jawatan' => $request->jawatan,
            ]);

            return redirect()->route('pentadbir.jawatan')
                ->with('success', 'Jawatan berjaya ditambah!');
        }

        $rows = Jawatan::orderBy('jawatan', 'asc')->get();
        return view('pentadbir.jawatan', compact('rows'));
    }

    public function gred(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'gred' => 'required|string|max:50',
                'keutamaan' => 'required|integer',
            ]);

            Gred::create([
                'gred' => $request->gred,
                'keutamaan' => $request->keutamaan,
            ]);

            return redirect()->route('pentadbir.gred')
                ->with('success', 'Gred berjaya ditambah!');
        }

        $rows = Gred::orderBy('keutamaan', 'desc')->get();
        return view('pentadbir.gred', compact('rows'));
    }

    public function perjawatan(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'jawatan' => 'required|integer',
                'gred' => 'required|integer',
                'program' => 'required|integer',
                'unit' => 'required|integer',
            ]);

            Perjawatan::create([
                'jawatan' => $request->jawatan,
                'gred' => $request->gred,
                'program' => $request->program,
                'unit' => $request->unit,
            ]);

            return redirect()->route('pentadbir.perjawatan')
                ->with('success', 'Perjawatan berjaya ditambah!');
        }

        $rows = Perjawatan::with(['jawatanRel', 'gredRel', 'organisasiRel', 'unitRel'])
            ->orderBy('id', 'asc')
            ->get();
        $jawatan_list = Jawatan::orderBy('jawatan', 'asc')->get();
        $gred_list = Gred::orderBy('keutamaan', 'desc')->get();
        $program_list = Organisasi::orderBy('program', 'asc')->get();
        $unit_list = Unit::orderBy('unit', 'asc')->get();

        return view('pentadbir.perjawatan', compact('rows', 'jawatan_list', 'gred_list', 'program_list', 'unit_list'));
    }

    public function elaun(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'nama' => 'required|string|max:200',
            ]);

            Elaun::create([
                'nama' => $request->nama,
            ]);

            return redirect()->route('pentadbir.elaun')
                ->with('success', 'Elaun berjaya ditambah!');
        }

        $rows = Elaun::orderBy('nama', 'asc')->get();
        return view('pentadbir.elaun', compact('rows'));
    }

    public function motoHariPekerja(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'tahun' => 'required|integer',
                'moto' => 'required|string|max:200',
            ]);

            MotoHariPekerja::create([
                'tahun' => $request->tahun,
                'moto' => $request->moto,
            ]);

            return redirect()->route('pentadbir.moto_hari_pekerja')
                ->with('success', 'Moto hari pekerja berjaya ditambah!');
        }

        $rows = MotoHariPekerja::orderBy('tahun', 'desc')->get();
        return view('pentadbir.moto_hari_pekerja', compact('rows'));
    }

    // ========================================
    // SURAT PENGESAHAN METHODS
    // ========================================

    public function suratPengesahanCari(Request $request)
    {
        $nama = $request->input('nama', '');

        $rows = collect();
        if ($request->isMethod('post') && $nama) {
            $rows = \App\Models\SuratPengesahan::select(
                'surat_pengesahan.*',
                'kakitangan.nama'
            )
                ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_pengesahan.idkakitangan')
                ->where('kakitangan.nama', 'LIKE', '%' . $nama . '%')
                ->orderBy('kakitangan.nama', 'asc')
                ->orderBy('surat_pengesahan.id', 'asc')
                ->get();
        }

        $pelulus = \App\Models\SuratPengesahanPelulus::orderBy('id', 'desc')->first();

        return view('pentadbir.surat_pengesahan_cari', compact('rows', 'nama', 'pelulus'));
    }

    public function suratPengesahanPelulus(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'idpelulus' => 'required|integer',
                'tarikh' => 'required|date',
            ]);

            \App\Models\SuratPengesahanPelulus::create([
                'idpelulus' => $request->idpelulus,
                'pengguna' => auth()->user()->id,
                'tarikh' => $request->tarikh,
            ]);

            return redirect()->route('pentadbir.surat_pengesahan_pelulus')
                ->with('success', 'Pelulus berjaya ditambah!');
        }

        $rows = \App\Models\SuratPengesahanPelulus::select(
            'surat_pengesahan_pelulus.*',
            'kakitangan.nama'
        )
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_pengesahan_pelulus.idpelulus')
            ->orderBy('surat_pengesahan_pelulus.tarikh', 'desc')
            ->get();

        $kakitangan_list = \App\Models\Kakitangan::orderBy('nama', 'asc')->get();

        return view('pentadbir.surat_pengesahan_pelulus', compact('rows', 'kakitangan_list'));
    }

    // ========================================
    // SURAT AKUAN PERUBATAN METHODS
    // ========================================

    public function suratAkuanSenarai(Request $request)
    {
        $rows = \App\Models\SuratAkuanPerubatan::select(
            'surat_akuan_perubatan.id',
            'surat_akuan_perubatan.hospital',
            'surat_akuan_perubatan.no_rujukan',
            'kakitangan.nama as namakakitangan',
            'keluarga.nama as pesakit'
        )
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_akuan_perubatan.idkakitangan')
            ->leftJoin('keluarga', 'keluarga.id', '=', 'surat_akuan_perubatan.pesakit')
            ->orderBy('kakitangan.nama', 'asc')
            ->get();

        return view('pentadbir.surat_akuan_senarai', compact('rows'));
    }

    public function suratAkuanPelulus(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'idpelulus' => 'required|integer',
                'tarikh' => 'required|date',
            ]);

            \App\Models\SuratAkuanPerubatanPelulus::create([
                'idpelulus' => $request->idpelulus,
                'pengguna' => auth()->user()->id,
                'tarikh' => $request->tarikh,
            ]);

            return redirect()->route('pentadbir.surat_akuan_pelulus')
                ->with('success', 'Pelulus berjaya ditambah!');
        }

        $rows = \App\Models\SuratAkuanPerubatanPelulus::select(
            'surat_akuan_perubatan_pelulus.tarikh',
            'surat_akuan_perubatan_pelulus.id',
            'kakitangan.nama'
        )
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_akuan_perubatan_pelulus.idkakitangan')
            ->orderBy('id', 'desc')
            ->get();

        $kakitangan_list = \App\Models\Kakitangan::orderBy('nama', 'asc')->get();

        return view('pentadbir.surat_akuan_pelulus', compact('rows', 'kakitangan_list'));
    }
}
