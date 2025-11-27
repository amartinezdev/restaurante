<!-- CARRITO -->
<form id="form-carrito" action="" method="post">
    <p id="carrito-vacio" class="text-center text-muted<?php echo empty($_SESSION['carrito']) ? '' : ' d-none'; ?>">
        No hay productos en el pedido.
    </p>

    <div id="carrito-contenido" class="<?php echo empty($_SESSION['carrito']) ? 'd-none' : ''; ?>">
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Comentario</th>
                        <th class="text-center">Subtotal</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="carrito-tbody">
                    <?php
                    $total = 0.0;
                    foreach ($_SESSION["carrito"] as $idProd => $cant) {
                        // se recupera nombre y precio del producto
                        $consulta2 = mysqli_query($conn, "SELECT nombre, precio FROM producto WHERE id = $idProd");
                        $producto = mysqli_fetch_assoc($consulta2);

                        if (!$producto) {
                            continue;
                        }

                        $nombre = $producto['nombre'];
                        $precio = $producto['precio'];
                        $subtotal = $precio * $cant;
                        $total += $subtotal;

                        // para el máximo y evitar que no puedan pedir más del stock que haya:
                        $consultaStock = mysqli_query($conn, "SELECT stock FROM producto WHERE id = '$idProd'");
                        $rowStock = mysqli_fetch_array($consultaStock);
                    ?>
                        <tr class="carrito-row" data-id="<?php echo $idProd ?>">
                            <td class="carrito-nombre"><?php echo $nombre ?></td>
                            <td class="text-center"><?php echo number_format($precio, 2) ?> €</td>
                            <td class="text-center" style="max-width:120px;">
                                <input type="number"
                                    name="cantidades[<?php echo $idProd ?>]"
                                    value="<?php echo $cant ?>"
                                    min="1" max="<?php echo $rowStock["stock"] ?>"
                                    class="form-control text-center carrito-cantidad-input"
                                    data-id="<?php echo $idProd ?>">
                                <small class="small text-muted text-start">Máx: <?php echo $rowStock["stock"] ?></small>
                            </td>
                            <td>
                                <textarea
                                    name="comentario[<?php echo $idProd ?>]"
                                    class="form-control text-center carrito-comentario" maxlength="15"
                                    placeholder="Comentario... (Opcional)"></textarea>
                            </td>
                            <td class="text-center carrito-subtotal" data-id="<?php echo $idProd ?>"><?php echo number_format($subtotal, 2) ?> €</td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-outline-danger carrito-remove" data-id="<?php echo $idProd ?>" href="carta.php?remove=<?php echo $idProd ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th class="text-center" id="carrito-total"><?php echo number_format($total, 2) ?> €</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex gap-2 justify-content-end mt-4">
            <a class="btn btn-outline-secondary carrito-clear" href="carta.php?limpiar=1">Vaciar</a>
            <button type="submit" name="pedir" value="1" class="btn btn-success">Pedir</button>
        </div>
    </div>
</form>
