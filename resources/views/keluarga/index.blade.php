@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Senarai Keluarga</h1>

        <a href="{{ route('keluarga.create') }}" class="btn btn-primary mb-3">Tambah Ahli Keluarga</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Bil</th>
                    <th>Nama</th>
                    <th>Hubungan</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->hubunganInfo->hubungan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('keluarga.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('keluarga.destroy', $row->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Padam rekod ini?')" class="btn btn-sm btn-danger">
                                    Padam
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection