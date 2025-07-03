@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Préstamos Vencidos</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Libro</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Vencimiento</th>
                <th>Días de Retraso</th>
            </tr>
        </thead>
        <tbody>
            @forelse($overdueLoans as $loan)
                <tr>
                    <td>{{ $loan->user->name }}</td>
                    <td>{{ $loan->book->title }}</td>
                    <td>{{ $loan->loan_date }}</td>
                    <td>{{ $loan->due_date }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->due_date)->diffInDays(now()) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay préstamos vencidos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 