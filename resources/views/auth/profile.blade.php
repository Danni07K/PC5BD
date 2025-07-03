@extends('layouts.app')

@section('title', 'Mi Perfil - Biblioteca Virtual')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2"></i>
                Mi Perfil
            </h1>
            <p class="text-muted">Información de tu cuenta</p>
        </div>
    </div>

    <div class="row">
        <!-- Información del Usuario -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información Personal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre:</label>
                        <p class="text-muted">{{ $user->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rol:</label>
                        <span class="badge bg-{{ $user->role === 'bibliotecario' ? 'primary' : 'success' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Miembro desde:</label>
                        <p class="text-muted">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Préstamos -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Mi Historial de Préstamos
                    </h6>
                    <a href="{{ route('loans.history') }}" class="btn btn-sm btn-primary">
                        Ver Historial Completo
                    </a>
                </div>
                <div class="card-body">
                    @if($loans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Libro</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Fecha Devolución</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                    <tr>
                                        <td>
                                            <strong>{{ $loan->book->title }}</strong><br>
                                            <small class="text-muted">{{ $loan->book->author }}</small>
                                        </td>
                                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                        <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                        <td>
                                            @if($loan->status === 'active')
                                                @if($loan->isOverdue())
                                                    <span class="badge bg-danger">Vencido</span>
                                                @else
                                                    <span class="badge bg-success">Activo</span>
                                                @endif
                                            @elseif($loan->status === 'returned')
                                                <span class="badge bg-secondary">Devuelto</span>
                                            @else
                                                <span class="badge bg-warning">{{ ucfirst($loan->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($loan->status === 'active')
                                                <a href="{{ route('loans.return-form', $loan->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-undo me-1"></i>Devolver
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No tienes préstamos registrados</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Buscar Libros
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas Personales -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Préstamos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $loans->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Préstamos Activos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $loans->where('status', 'active')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Préstamos Vencidos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $loans->where('status', 'active')->filter(function($loan) { return $loan->isOverdue(); })->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 