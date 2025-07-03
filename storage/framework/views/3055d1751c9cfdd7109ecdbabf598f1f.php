
<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Libros</h1>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <a href="<?php echo e(route('books.create')); ?>" class="btn btn-primary mb-3">Agregar Libro</a>
    <?php if($books->count()): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Disponibles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($book->id); ?></td>
                <td><?php echo e($book->title); ?></td>
                <td><?php echo e($book->author); ?></td>
                <td><?php echo e($book->isbn); ?></td>
                <td><?php echo e($book->category); ?></td>
                <td><?php echo e($book->quantity); ?></td>
                <td><?php echo e($book->available_quantity); ?></td>
                <td>
                    <a href="<?php echo e(route('books.show', $book->id)); ?>" class="btn btn-info btn-sm">Ver</a>
                    <?php if(auth()->user()->role === 'bibliotecario'): ?>
                        <a href="<?php echo e(route('books.edit', $book->id)); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <form action="<?php echo e(route('books.destroy', $book->id)); ?>" method="POST" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar este libro?')">Eliminar</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($books->links()); ?>

    <?php else: ?>
        <p>No hay libros registrados.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BD5\nueva-biblioteca\resources\views/books/index.blade.php ENDPATH**/ ?>