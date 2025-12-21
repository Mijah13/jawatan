<?php
namespace App\Http\Controllers;

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

    public function peringkatSumbanganEdit($id)
    {
        $item = PeringkatSumbangan::findOrFail($id);
        return view('pentadbir.peringkat_sumbangan_edit', compact('item'));
    }

    public function peringkatSumbanganUpdate(Request $request, $id)
    {
        $request->validate([
            'peringkat' => 'required|string|max:50',
        ]);

        $item = PeringkatSumbangan::findOrFail($id);
        $item->update([
            'peringkat' => $request->peringkat,
        ]);

        return redirect()->route('pentadbir.peringkat_sumbangan')
            ->with('success', 'Peringkat sumbangan berjaya dikemaskini!');
    }

    public function peringkatSumbanganDestroy($id)
    {
        PeringkatSumbangan::findOrFail($id)->delete();

        return redirect()->route('pentadbir.peringkat_sumbangan')
            ->with('success', 'Peringkat sumbangan berjaya dipadam!');
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

    public function programEdit($id)
    {
        $item = Organisasi::findOrFail($id);
        return view('pentadbir.program_edit', compact('item'));
    }

    public function programUpdate(Request $request, $id)
    {
        $request->validate([
            'kod' => 'required|string|max:50',
            'nama' => 'required|string|max:200',
        ]);

        $item = Organisasi::findOrFail($id);
        $item->update([
            'kod' => $request->kod,
            'program' => $request->nama,
        ]);

        return redirect()->route('pentadbir.program')
            ->with('success', 'Program berjaya dikemaskini!');
    }

    public function programDestroy($id)
    {
        Organisasi::findOrFail($id)->delete();

        return redirect()->route('pentadbir.program')
            ->with('success', 'Program berjaya dipadam!');
    }

    public function unit(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'unit' => 'required|string|max:50',
                'program' => 'required|integer',
            ]);

            Unit::create([
                'unit' => $request->unit,
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

    public function unitEdit($id)
    {
        $item = Unit::findOrFail($id);
        $program_list = Organisasi::orderBy('program', 'asc')->get();

        return view('pentadbir.unit_edit', compact('item', 'program_list'));
    }

    public function unitUpdate(Request $request, $id)
    {
        $request->validate([
            'program' => 'required|integer',
            'unit' => 'required|string|max:200',
        ]);


        $item = Unit::findOrFail($id);
        $item->update([
            'program' => $request->program,
            'unit' => $request->unit,
        ]);

        return redirect()->route('pentadbir.unit')
            ->with('success', 'Unit berjaya dikemaskini!');
    }

    public function unitDestroy($id)
    {
        Unit::findOrFail($id)->delete();

        return redirect()->route('pentadbir.unit')
            ->with('success', 'Unit berjaya dipadam!');
    }

    public function jenisIsytiharDestroy($id)
    {
        JenisIsytihar::findOrFail($id)->delete();

        return redirect()->route('pentadbir.jenis_isytihar')
            ->with('success', 'Jenis isytihar berjaya dipadam!');
    }

    public function jenisIsytiharEdit($id)
    {
        $item = JenisIsytihar::findOrFail($id);
        return view('pentadbir.jenis_isytihar_edit', compact('item'));
    }

    public function jenisIsytiharUpdate(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|string|max:200',
        ]);

        $item = JenisIsytihar::findOrFail($id);
        $item->update([
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('pentadbir.jenis_isytihar')
            ->with('success', 'Jenis isytihar berjaya dikemaskini!');
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

    public function jenisPenempatanEdit($id)
    {
        $item = Penempatan::findOrFail($id);
        return view('pentadbir.jenis_penempatan_edit', compact('item'));
    }
    public function jenisPenempatanUpdate(Request $request, $id)
    {
        $request->validate([
            'kod' => 'required|string|max:10',
            'jenis' => 'required|string|max:200',
        ]);

        $item = Penempatan::findOrFail($id);
        $item->update([
            'kod' => $request->kod,
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('pentadbir.jenis_penempatan')
            ->with('success', 'Jenis penempatan berjaya dikemaskini!');
    }
    public function jenisPenempatanDestroy($id)
    {
        Penempatan::findOrFail($id)->delete();

        return redirect()->route('pentadbir.jenis_penempatan')
            ->with('success', 'Jenis penempatan berjaya dipadam!');
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

    public function jawatanEdit($id)
    {
        $item = Jawatan::findOrFail($id);
        return view('pentadbir.jawatan_edit', compact('item'));
    }
    public function jawatanUpdate(Request $request, $id)
    {
        $request->validate([
            'kod' => 'required|string|max:50',
            'jawatan' => 'required|string|max:200',
        ]);

        $item = Jawatan::findOrFail($id);
        $item->update([
            'kod' => $request->kod,
            'jawatan' => $request->jawatan,
        ]);

        return redirect()->route('pentadbir.jawatan')
            ->with('success', 'Jawatan berjaya dikemaskini!');
    }
    public function jawatanDestroy($id)
    {
        Jawatan::findOrFail($id)->delete();

        return redirect()->route('pentadbir.jawatan')
            ->with('success', 'Jawatan berjaya dipadam!');
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

    public function gredEdit($id)
    {
        $item = Gred::findOrFail($id);
        return view('pentadbir.gred_edit', compact('item'));
    }
    public function gredUpdate(Request $request, $id)
    {
        $request->validate([
            'gred' => 'required|string|max:50',
            'keutamaan' => 'required|integer',
        ]);

        $item = Gred::findOrFail($id);
        $item->update([
            'gred' => $request->gred,
            'keutamaan' => $request->keutamaan,
        ]);

        return redirect()->route('pentadbir.gred')
            ->with('success', 'Gred berjaya dikemaskini!');
    }
    public function gredDestroy($id)
    {
        Gred::findOrFail($id)->delete();

        return redirect()->route('pentadbir.gred')
            ->with('success', 'Gred berjaya dipadam!');
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

    public function perjawatanEdit($id)
    {
        $item = Perjawatan::findOrFail($id);
        $jawatan_list = Jawatan::orderBy('jawatan', 'asc')->get();
        $gred_list = Gred::orderBy('keutamaan', 'desc')->get();

        return view('pentadbir.perjawatan_edit', compact('item', 'jawatan_list', 'gred_list'));
    }
    public function perjawatanUpdate(Request $request, $id)
    {
        $request->validate([
            'jawatan' => 'required|integer',
            'gred' => 'required|integer',
            'waran' => 'required|string|max:50',
            'bilanganperjawatan' => 'required|integer',
        ]);

        $item = Perjawatan::findOrFail($id);
        $item->update([
            'jawatan' => $request->jawatan,
            'gred' => $request->gred,
            'waran' => $request->waran,
            'bilanganperjawatan' => $request->bilanganperjawatan,
        ]);

        return redirect()->route('pentadbir.perjawatan')
            ->with('success', 'Perjawatan berjaya dikemaskini!');
    }
    public function perjawatanDestroy($id)
    {
        Perjawatan::findOrFail($id)->delete();

        return redirect()->route('pentadbir.perjawatan')
            ->with('success', 'Perjawatan berjaya dipadam!');
    }
    public function perjawatan(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'jawatan' => 'required|integer',
                'gred' => 'required|integer',
                'waran' => 'required|string|max:50',
                'bilanganperjawatan' => 'required|integer',
            ]);

            Perjawatan::create([
                'jawatan' => $request->jawatan,
                'gred' => $request->gred,
                'waran' => $request->waran,
                'bilanganperjawatan' => $request->bilanganperjawatan,
            ]);

            return redirect()->route('pentadbir.perjawatan')
                ->with('success', 'Perjawatan berjaya ditambah!');
        }

        $rows = Perjawatan::with(['jawatanRel', 'gredRel'])
            ->orderBy('id', 'asc')
            ->get();
        $jawatan_list = Jawatan::orderBy('jawatan', 'asc')->get();
        $gred_list = Gred::orderBy('keutamaan', 'desc')->get();

        return view('pentadbir.perjawatan', compact('rows', 'jawatan_list', 'gred_list', ));
    }

    public function elaunEdit($id)
    {
        $item = Elaun::findOrFail($id);
        return view('pentadbir.elaun_edit', compact('item'));
    }
    public function elaunUpdate(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
        ]);

        $item = Elaun::findOrFail($id);
        $item->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('pentadbir.elaun')
            ->with('success', 'Elaun berjaya dikemaskini!');
    }
    public function elaunDestroy($id)
    {
        Elaun::findOrFail($id)->delete();

        return redirect()->route('pentadbir.elaun')
            ->with('success', 'Elaun berjaya dipadam!');
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

    public function motoHariPekerjaEdit($id)
    {
        $item = MotoHariPekerja::findOrFail($id);
        return view('pentadbir.moto_hari_pekerja_edit', compact('item'));
    }
    public function motoHariPekerjaUpdate(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'moto' => 'required|string|max:200',
        ]);

        $item = MotoHariPekerja::findOrFail($id);
        $item->update([
            'tahun' => $request->tahun,
            'moto' => $request->moto,
        ]);

        return redirect()->route('pentadbir.moto_hari_pekerja')
            ->with('success', 'Moto hari pekerja berjaya dikemaskini!');
    }
    public function motoHariPekerjaDestroy($id)
    {
        MotoHariPekerja::findOrFail($id)->delete();

        return redirect()->route('pentadbir.moto_hari_pekerja')
            ->with('success', 'Moto hari pekerja berjaya dipadam!');
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

    public function suratAkuanSenaraiEdit($id)
    {
        $item = \App\Models\SuratAkuanPerubatan::select(
            'surat_akuan_perubatan.id',
            'surat_akuan_perubatan.hospital',
            'surat_akuan_perubatan.no_rujukan',
            'kakitangan.nama as namakakitangan',
            'keluarga.nama as pesakit'
        )
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_akuan_perubatan.idkakitangan')
            ->leftJoin('keluarga', 'keluarga.id', '=', 'surat_akuan_perubatan.pesakit')
            ->orderBy('kakitangan.nama', 'asc')
            ->where('surat_akuan_perubatan.id', $id)
            ->firstOrFail();

        // dd($item);


        return view('pentadbir.surat_akuan_senarai_edit', compact('item'));
    }
    public function suratAkuanSenaraiUpdate(Request $request, $id)
    {
        $request->validate([
            'no_rujukan' => 'required|string|max:255',
        ]);

        $item = \App\Models\SuratAkuanPerubatan::findOrFail($id);
        $item->update([
            'no_rujukan' => $request->no_rujukan,
        ]);

        return redirect()->route('pentadbir.surat_akuan_senarai')
            ->with('success', 'Surat akuan perubatan berjaya dikemaskini!');
    }

    public function suratAkuanSenaraiDestroy($id)
    {
        \App\Models\SuratAkuanPerubatan::findOrFail($id)->delete();

        return redirect()->route('pentadbir.surat_akuan_senarai')
            ->with('success', 'Surat akuan perubatan berjaya dipadam!');
    }

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

    public function suratAkuanPelulusEdit($id)
    {
        $item = \App\Models\SuratAkuanPerubatanPelulus::findOrFail($id);
        $kakitangan_list = \App\Models\Kakitangan::orderBy('nama', 'asc')->get();

        return view('pentadbir.surat_akuan_pelulus_edit', compact('item', 'kakitangan_list'));
    }

    public function suratAkuanPelulusUpdate(Request $request, $id)
    {
        $request->validate([
            'tarikh' => 'required|date',
        ]);

        $item = \App\Models\SuratAkuanPerubatanPelulus::findOrFail($id);
        $item->update([
            'tarikh' => $request->tarikh,
        ]);

        return redirect()->route('pentadbir.surat_akuan_pelulus')
            ->with('success', 'Pelulus berjaya dikemaskini!');
    }

    public function suratAkuanPelulusDestroy($id)
    {
        \App\Models\SuratAkuanPerubatanPelulus::findOrFail($id)->delete();

        return redirect()->route('pentadbir.surat_akuan_pelulus')
            ->with('success', 'Pelulus berjaya dipadam!');
    }

    public function suratAkuanPelulus(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'idkakitangan' => 'required|integer',
                'tarikh' => 'required|date',
            ]);

            \App\Models\SuratAkuanPerubatanPelulus::create([
                'idkakitangan' => $request->idkakitangan,
                'tarikh' => $request->tarikh,
            ]);

            return redirect()->route('pentadbir.surat_akuan_pelulus')
                ->with('success', 'Pelulus berjaya ditambah!');
        }

        $rows = \App\Models\SuratAkuanPerubatanPelulus::select(
            'surat_akuan_perubatan_pelulus.tarikh',
            'surat_akuan_perubatan_pelulus.id',
            'kakitangan.nama',
        )
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_akuan_perubatan_pelulus.idkakitangan')
            ->orderBy('id', 'desc')
            ->get();

        $kakitangan_list = \App\Models\Kakitangan::orderBy('nama', 'asc')->get();

        return view('pentadbir.surat_akuan_pelulus', compact('rows', 'kakitangan_list'));
    }
}
