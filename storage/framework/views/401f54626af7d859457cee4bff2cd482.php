
<?php $__env->startSection('content'); ?>
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
            <?php $__empty_1 = true; $__currentLoopData = $overdueLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loan->user->name); ?></td>
                    <td><?php echo e($loan->book->title); ?></td>
                    <td><?php echo e($loan->loan_date); ?></td>
                    <td><?php echo e($loan->due_date); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($loan->due_date)->diffInDays(now())); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">No hay préstamos vencidos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BD5\nueva-biblioteca\resources\views/loans/overdue.blade.php ENDPATH**/ ?>