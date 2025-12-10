@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Ahli Keluarga</h1>

        <form method="POST" action="{{ route('keluarga.update', $keluarga->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $keluarga->nama }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Hubungan</label>
                <select name="hubungan" class="form-control" required>
                    @foreach($hubungan as $h)
                        <option value="{{ $h->id }}" {{ $keluarga->hubungan == $h->id ? 'selected' : '' }}>
                            {{ $h->hubungan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success">Kemaskini</button>
            <a href="{{ route('keluarga.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection