

<?php $__env->startSection('title', 'Mi Perfil - Biblioteca Virtual'); ?>

<?php $__env->startSection('content'); ?>
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
                        <p class="text-muted"><?php echo e($user->name); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <p class="text-muted"><?php echo e($user->email); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rol:</label>
                        <span class="badge bg-<?php echo e($user->role === 'bibliotecario' ? 'primary' : 'success'); ?>">
                            <?php echo e(ucfirst($user->role)); ?>

                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Miembro desde:</label>
                        <p class="text-muted"><?php echo e($user->created_at->format('d/m/Y')); ?></p>
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
                    <a href="<?php echo e(route('loans.history')); ?>" class="btn btn-sm btn-primary">
                        Ver Historial Completo
                    </a>
                </div>
                <div class="card-body">
                    <?php if($loans->count() > 0): ?>
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
                                    <?php $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($loan->book->title); ?></strong><br>
                                            <small class="text-muted"><?php echo e($loan->book->author); ?></small>
                                        </td>
                                        <td><?php echo e($loan->loan_date->format('d/m/Y')); ?></td>
                                        <td><?php echo e($loan->due_date->format('d/m/Y')); ?></td>
                                        <td>
                                            <?php if($loan->status === 'active'): ?>
                                                <?php if($loan->isOverdue()): ?>
                                                    <span class="badge bg-danger">Vencido</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php endif; ?>
                                            <?php elseif($loan->status === 'returned'): ?>
                                                <span class="badge bg-secondary">Devuelto</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning"><?php echo e(ucfirst($loan->status)); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($loan->status === 'active'): ?>
                                                <a href="<?php echo e(route('loans.return-form', $loan->id)); ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-undo me-1"></i>Devolver
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No tienes préstamos registrados</p>
                            <a href="<?php echo e(route('books.index')); ?>" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Buscar Libros
                            </a>
                        </div>
                    <?php endif; ?>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($loans->count()); ?></div>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($loans->where('status', 'active')->count()); ?></div>
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
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($loans->where('status', 'active')->filter(function($loan) { return $loan->isOverdue(); })->count()); ?></div>
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
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BD5\nueva-biblioteca\resources\views/auth/profile.blade.php ENDPATH**/ ?>