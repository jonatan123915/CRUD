<?php
include 'conexion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'agregar') {
        $tipo_tamal = $_POST['tipo_tamal'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];

        $stmt = $conn->prepare("INSERT INTO ventas (tipo_tamal, cantidad, precio) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $tipo_tamal, $cantidad, $precio);

        if ($stmt->execute()) {
            echo "Venta de tamal agregada exitosamente.";
        } else {
            echo "Error al agregar la venta de tamal: " . $conn->error;
        }
        $stmt->close();
    } elseif ($action == 'eliminar') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM ventas WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>window.location.href='ventas.php';</script>";
        } else {
            echo "Error al eliminar la venta de tamal: " . $conn->error;
        }
        $stmt->close();
    } elseif ($action == 'modificar') {
        $id = $_POST['id'];
        $tipo_tamal = $_POST['tipo_tamal'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];

        $stmt = $conn->prepare("UPDATE ventas SET tipo_tamal=?, cantidad=?, precio=? WHERE id=?");
        $stmt->bind_param("sidi", $tipo_tamal, $cantidad, $precio, $id);

        if ($stmt->execute()) {
            echo "<script>window.location.href='ventas.php';</script>";
        } else {
            echo "Error al modificar la venta de tamal: " . $conn->error;
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM ventas");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Ventas de Tamales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #77aaff 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container input[type="text"],
        .form-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
        }
        .form-container button {
            background: #333;
            color: #fff;
            border: 0;
            padding: 10px 20px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #555;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f4f4f4;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Sistema de Ventas de Tamales</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="form-container">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <form method="POST" action="ventas.php">
                <input type="hidden" name="action" value="agregar">
                Tipo de Tamal: <input type="text" name="tipo_tamal" required>
                Cantidad: <input type="number" name="cantidad" required>
                Precio: <input type="number" step="0.01" name="precio" required>
                <button type="submit">Agregar Venta</button>
            </form>
        </div>

        <table>
            <tr>
                <th>Tipo de Tamal</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['tipo_tamal']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio']); ?></td>
                    <td>
                        <form method="POST" action="ventas.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="hidden" name="action" value="modificar">
                            Tipo de Tamal: <input type="text" name="tipo_tamal" value="<?php echo htmlspecialchars($row['tipo_tamal']); ?>" required>
                            Cantidad: <input type="number" name="cantidad" value="<?php echo htmlspecialchars($row['cantidad']); ?>" required>
                            Precio: <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($row['precio']); ?>" required>
                            <button type="submit">Modificar</button>
                        </form>
                        <form method="POST" action="ventas.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="hidden" name="action" value="eliminar">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>