@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Historial de Préstamos</h1>
    <a href="{{ route('loans.index') }}" class="btn btn-secondary mb-3">Volver al listado</a>
    @if($loans->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Libro</th>
                <th>Usuario</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->id }}</td>
                <td>{{ $loan->book->title ?? '-' }}</td>
                <td>{{ $loan->user->name ?? '-' }}</td>
                <td>{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : '-' }}</td>
                <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
                <td>{{ $loan->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No hay préstamos en el historial.</p>
    @endif
</div>
@endsection 