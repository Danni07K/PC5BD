@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Préstamos</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <a href="{{ route('loans.create') }}" class="btn btn-primary mb-3">Registrar Préstamo</a>
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
                <th>Acciones</th>
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
                <td>
                    @if($loan->status === 'active')
                        <a href="{{ route('loans.return-form', $loan->id) }}" class="btn btn-success btn-sm">Devolver</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $loans->links() }}
    @else
        <p>No hay préstamos registrados.</p>
    @endif
</div>
@endsection 