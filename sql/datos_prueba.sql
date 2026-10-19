/* INSERTS de prueba */

INSERT INTO categoria (nombre, margen) VALUES
('Lácteos', 40),
('Copetín', 40),
('Gaseosas', 35),
('Cervezas', 30);

INSERT INTO proveedor (nombre) VALUES
('Distribuidora Andina'),
('Distribuidora Coca Cola'),
('Lacteos Don Angel');

INSERT INTO producto (nombre, categoria_id, proveedor_id, unidad_medida, costo, precio_venta, stock_actual, stock_minimo) VALUES
('Dulce de Leche Repostero Pote - 500gr', 1, 3, 'unidad', 1200, 1680, 5, 2),
('Cerveza Quilmes de 1 Litro', 4, 1, 'unidad', 2500, 3250, 12, 4),
('Coca Cola de 2 Lts.', 3, 2, 'unidad', 2600, 3510, 10, 3),
('Bolsa de papas fritas sueltas de 1 KG', 2, 1, 'kg', 5000, 7000, 1.000, 0.500);