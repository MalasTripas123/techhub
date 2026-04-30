<?php
$pageTitle = 'Mi Carrito';
include BASE_PATH . '/app/views/layout/header.php';
?>

<div class="container py-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 text-warning"></i> Mi Carrito</h2>

    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-3 text-muted"></i>
            <h4 class="mt-3 text-muted">Tu carrito está vacío</h4>
            <a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo" class="btn btn-dark mt-3">
                <i class="bi bi-arrow-left"></i> Ver productos
            </a>
        </div>
    <?php else: ?>
        <div class="row gy-4">
            <!-- Items del carrito -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" id="tablaCarrito">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="carritoCuerpo">
                                    <?php foreach ($items as $id => $item): ?>
                                        <tr id="fila-<?= $id ?>">
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($item['imagen']) ?>"
                                                        alt="<?= htmlspecialchars($item['nombre']) ?>"
                                                        width="60" height="60" class="object-fit-contain bg-light rounded p-1"
                                                        onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'">
                                                    <div>
                                                        <div class="fw-semibold"><?= htmlspecialchars($item['nombre']) ?></div>
                                                        <div class="text-muted small">
                                                            $<?= number_format($item['precio'], 0, ',', '.') ?> c/u
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center" style="width:140px">
                                                <div class="input-group input-group-sm justify-content-center">
                                                    <button class="btn btn-outline-secondary btn-menos" data-id="<?= $id ?>">−</button>
                                                    <input type="number" class="form-control text-center cant-input"
                                                        value="<?= $item['cantidad'] ?>" min="1" max="99"
                                                        data-id="<?= $id ?>" data-precio="<?= $item['precio'] ?>" style="width:50px">
                                                    <button class="btn btn-outline-secondary btn-mas" data-id="<?= $id ?>">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold" id="subtotal-<?= $id ?>">
                                                $<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-outline-danger btn-sm btn-eliminar" data-id="<?= $id ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top:80px">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Resumen del pedido</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span id="totalCarrito" class="fw-bold"><?= $total ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Envío</span>
                            <span class="text-success fw-semibold">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span id="totalFinal" class="fw-bold fs-5 text-dark"><?= $total ?></span>
                        </div>
                        <a href="<?= BASE_URL ?>/index.php?controller=carrito&action=checkout"
                            class="btn btn-dark w-100 py-2 fw-bold"
                            onclick="return confirm('¿Confirmas tu compra?')">
                            <i class="bi bi-bag-check-fill"></i> Finalizar compra
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?controller=producto&action=catalogo"
                            class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left"></i> Seguir comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';

    // Eliminar item del carrito
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`${BASE_URL}/index.php?controller=carrito&action=eliminar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `producto_id=${id}`
                })
                .then(r => r.json())
                .then(data => {
                    if (data.exito) {
                        document.getElementById(`fila-${id}`).remove();
                        actualizarTotales(data.total);
                        if (data.total_items === 0) location.reload();
                    }
                });
        });
    });

    // Guardar el valor inicial al hacer focus o clic
    document.querySelectorAll('.cant-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.dataset.oldValue = this.value;
        });

        input.addEventListener('change', function() {
            const id = this.dataset.id;
            const nuevaCant = parseInt(this.value);
            if (nuevaCant <= 0) {
                document.querySelector(`.btn-eliminar[data-id="${id}"]`).click();
            } else {
                cambiarCantidad(id, nuevaCant, this);
            }
        });
    });

    // // 1. Agregar listener para el input manual (teclear números)
    // document.querySelectorAll('.cant-input').forEach(input => {
    //     input.addEventListener('change', function() {
    //         const id = this.dataset.id;
    //         const nuevaCant = parseInt(this.value);
    //         if (nuevaCant <= 0) {
    //             document.querySelector(`.btn-eliminar[data-id="${id}"]`).click();
    //         } else {
    //             cambiarCantidad(id, nuevaCant);
    //         }
    //     });
    // });


    // Botones + y -
    document.querySelectorAll('.btn-mas').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.cant-input[data-id="${id}"]`);
            input.dataset.oldValue = input.value; // Guardamos el valor seguro
            cambiarCantidad(id, parseInt(input.value) + 1);
        });
    });

    document.querySelectorAll('.btn-menos').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.cant-input[data-id="${id}"]`);
            const nueva = parseInt(input.value) - 1;
            input.dataset.oldValue = input.value; // Guardamos el valor seguro

            if (nueva <= 0) {
                document.querySelector(`.btn-eliminar[data-id="${id}"]`).click();
            } else {
                cambiarCantidad(id, nueva);
            }
        });
    });

    // 2. Reemplazar la función cambiarCantidad
    // Función actualizar modificada
    function cambiarCantidad(id, cantidad, inputElement = null) {
        fetch(`${BASE_URL}/index.php?controller=carrito&action=actualizar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `producto_id=${id}&cantidad=${cantidad}`
            })
            .then(r => r.json())
            .then(data => {
                const input = inputElement || document.querySelector(`.cant-input[data-id="${id}"]`);

                if (data.exito) {
                    input.value = cantidad;
                    input.dataset.oldValue = cantidad; // Actualizamos el valor seguro tras el éxito

                    // Actualizar subtotal
                    const precio = parseInt(input.dataset.precio);
                    const subtotal = precio * cantidad;
                    document.getElementById(`subtotal-${id}`).textContent = '$' + new Intl.NumberFormat('es-CL').format(subtotal);

                    // Actualizar totales generales
                    actualizarTotales(data.total, data.total_items);
                } else {
                    // Error (ej. falta de stock): Mostrar aviso y restaurar silenciosamente
                    // alert(data.mensaje);
                    if (input && input.dataset.oldValue) {
                        input.value = input.dataset.oldValue;
                    }
                }
            });
    }

    // 3. Reemplazar la función actualizarTotales
    function actualizarTotales(total, totalItems) {
        document.getElementById('totalCarrito').textContent = total;
        document.getElementById('totalFinal').textContent = total;

        // Actualizar dinámicamente el badge del navbar
        const badge = document.getElementById('carritoBadge');
        if (badge && totalItems !== undefined) {
            badge.textContent = totalItems;
            badge.style.display = totalItems > 0 ? 'inline-block' : 'none';
        }
    }
</script>

<?php include BASE_PATH . '/app/views/layout/footer.php'; ?>