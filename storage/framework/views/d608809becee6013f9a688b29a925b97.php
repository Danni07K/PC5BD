
<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Préstamos</h1>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <a href="<?php echo e(route('loans.create')); ?>" class="btn btn-primary mb-3">Registrar Préstamo</a>
    <?php if($loans->count()): ?>
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
            <?php $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($loan->id); ?></td>
                <td><?php echo e($loan->book->title ?? '-'); ?></td>
                <td><?php echo e($loan->user->name ?? '-'); ?></td>
                <td><?php echo e($loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : '-'); ?></td>
                <td><?php echo e($loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-'); ?></td>
                <td><?php echo e($loan->status); ?></td>
                <td>
                    <?php if($loan->status === 'active'): ?>
                        <a href="<?php echo e(route('loans.return-form', $loan->id)); ?>" class="btn btn-success btn-sm">Devolver</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($loans->links()); ?>

    <?php else: ?>
        <p>No hay préstamos registrados.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BD5\nueva-biblioteca\resources\views/loans/index.blade.php ENDPATH**/ ?>