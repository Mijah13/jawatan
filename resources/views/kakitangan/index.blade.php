@extends('layout')

@section('content')
<h1>Senarai Kakitangan</h1>

<table class="table">
@foreach ($rows as $row)
<tr>
  <td>{{ $row->nama }}</td>
  <td>{{ $row->nokp }}</td>
  <td><a href="{{ route('kakitangan.edit', $row->id) }}">Edit</a></td>
</tr>
@endforeach
</table>

@endsection
