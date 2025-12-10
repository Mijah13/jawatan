@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Ahli Keluarga</h1>

        <form method="POST" action="{{ route('keluarga.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Hubungan</label>
                <select name="hubungan" class="form-control" required>
                    @foreach($hubungan as $h)
                        <option value="{{ $h->id }}">{{ $h->hubungan }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('keluarga.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection