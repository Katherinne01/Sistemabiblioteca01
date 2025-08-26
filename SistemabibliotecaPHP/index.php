<?php
// Incluir las clases ANTES de iniciar la sesión
require_once 'classes/Biblioteca.php';
require_once 'classes/Libro.php';

// Ahora podemos iniciar la sesión
session_start();

// Configuración para mostrar errores (útil durante desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar la biblioteca si no existe en sesión
if (!isset($_SESSION['biblioteca'])) {
    $_SESSION['biblioteca'] = new Biblioteca();
    
    // Agregar algunos libros de ejemplo
    $libro1 = new Libro("Cien años de soledad", "Gabriel García Márquez", "9788437604947", "Novela", 1967);
    $libro2 = new Libro("1984", "George Orwell", "9780451524935", "Ciencia Ficción", 1949);
    $libro3 = new Libro("El principito", "Antoine de Saint-Exupéry", "9780156012195", "Fábula", 1943);
    
    $_SESSION['biblioteca']->agregarLibro($libro1);
    $_SESSION['biblioteca']->agregarLibro($libro2);
    $_SESSION['biblioteca']->agregarLibro($libro3);
}

$biblioteca = $_SESSION['biblioteca'];
$mensaje = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar_libro'])) {
        $titulo = htmlspecialchars($_POST['titulo']);
        $autor = htmlspecialchars($_POST['autor']);
        $isbn = htmlspecialchars($_POST['isbn']);
        $categoria = htmlspecialchars($_POST['categoria']);
        $anio_publicacion = intval($_POST['anio_publicacion']);
        
        $libro = new Libro($titulo, $autor, $isbn, $categoria, $anio_publicacion);
        if ($biblioteca->agregarLibro($libro)) {
            $mensaje = "Libro agregado con éxito.";
        } else {
            $mensaje = "Error: El libro ya existe.";
        }
    }
    
    if (isset($_POST['prestar_libro'])) {
        $isbn = htmlspecialchars($_POST['isbn_prestar']);
        $usuario = htmlspecialchars($_POST['usuario']);
        
        if ($biblioteca->prestarLibro($isbn, $usuario)) {
            $mensaje = "Libro prestado con éxito.";
        } else {
            $mensaje = "Error: No se pudo prestar el libro. Puede que no exista o ya esté prestado.";
        }
    }
    
    if (isset($_POST['devolver_libro'])) {
        $isbn = htmlspecialchars($_POST['isbn_devolver']);
        
        if ($biblioteca->devolverLibro($isbn)) {
            $mensaje = "Libro devuelto con éxito.";
        } else {
            $mensaje = "Error: No se pudo devolver el libro.";
        }
    }
    
    if (isset($_POST['buscar'])) {
        $termino = htmlspecialchars($_POST['termino_busqueda']);
        $criterio = htmlspecialchars($_POST['criterio_busqueda']);
        $resultados = $biblioteca->buscarLibros($termino, $criterio);
    }
    
    if (isset($_POST['eliminar_libro'])) {
        $isbn = htmlspecialchars($_POST['isbn_eliminar']);
        
        if ($biblioteca->eliminarLibro($isbn)) {
            $mensaje = "Libro eliminado con éxito.";
        } else {
            $mensaje = "Error: No se pudo eliminar el libro.";
        }
    }
}

// Obtener todos los libros para mostrar
$libros = $biblioteca->getLibros();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Biblioteca</title>
    <style>
        :root {
            --primary: #7a22cd90;
            --secondary: #cc7bb5d8;
            --accent: #d9ff00cf;
            --light: #f5f5f5;
            --dark: #333;
            --success: #28a79cff;
            --danger: #740210ff;
            --warning: #ffc107;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #df125659;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: var(--primary);
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        header h1 {
            text-align: center;
            font-size: 2.5rem;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card h2 {
            color: var(--primary);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: var(--secondary);
        }
        
        .btn-danger {
            background-color: var(--danger);
        }
        
        .btn-danger:hover {
            background-color: #a01997b1;
        }
        
        .btn-success {
            background-color: var(--success);
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: var(--secondary);
            color: white;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .status-prestado {
            color: var(--danger);
            font-weight: bold;
        }
        
        .status-disponible {
            color: var(--success);
            font-weight: bold;
        }
        
        .search-results {
            margin-top: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin: 1.5rem 0 1rem;
            color: var(--primary);
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            
            .form-row .form-group {
                width: 100%;
                margin-right: 0;
            }
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-row .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Sistema de Gestión de Biblioteca</h1>
        </div>
    </header>
    
    <div class="container">
        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo strpos($mensaje, 'éxito') !== false ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Agregar Nuevo Libro</h2>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="autor">Autor</label>
                        <input type="text" id="autor" name="autor" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <input type="text" id="categoria" name="categoria" required>
                    </div>
                    <div class="form-group">
                        <label for="anio_publicacion">Año de Publicación</label>
                        <input type="number" id="anio_publicacion" name="anio_publicacion" required min="1000" max="<?php echo date('Y'); ?>">
                    </div>
                </div>
                
                <button type="submit" name="agregar_libro">Agregar Libro</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Buscar Libros</h2>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="termino_busqueda">Término de Búsqueda</label>
                        <input type="text" id="termino_busqueda" name="termino_busqueda" required>
                    </div>
                    <div class="form-group">
                        <label for="criterio_busqueda">Criterio de Búsqueda</label>
                        <select id="criterio_busqueda" name="criterio_busqueda">
                            <option value="titulo">Título</option>
                            <option value="autor">Autor</option>
                            <option value="categoria">Categoría</option>
                            <option value="isbn">ISBN</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="buscar">Buscar</button>
            </form>
            
            <?php if (isset($resultados) && !empty($resultados)): ?>
                <div class="search-results">
                    <h3>Resultados de la Búsqueda</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>ISBN</th>
                                <th>Categoría</th>
                                <th>Año</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $libro): ?>
                                <tr>
                                    <td><?php echo $libro->getTitulo(); ?></td>
                                    <td><?php echo $libro->getAutor(); ?></td>
                                    <td><?php echo $libro->getIsbn(); ?></td>
                                    <td><?php echo $libro->getCategoria(); ?></td>
                                    <td><?php echo $libro->getAnioPublicacion(); ?></td>
                                    <td class="<?php echo $libro->estaPrestado() ? 'status-prestado' : 'status-disponible'; ?>">
                                        <?php echo $libro->estaPrestado() ? 'Prestado' : 'Disponible'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif (isset($resultados) && empty($resultados)): ?>
                <p>No se encontraron resultados.</p>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Gestión de Préstamos</h2>
            <div class="form-row">
                <div class="form-group">
                    <h3>Prestar Libro</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="isbn_prestar">ISBN del Libro</label>
                            <input type="text" id="isbn_prestar" name="isbn_prestar" required>
                        </div>
                        <div class="form-group">
                            <label for="usuario">Nombre del Usuario</label>
                            <input type="text" id="usuario" name="usuario" required>
                        </div>
                        <button type="submit" name="prestar_libro" class="btn-success">Prestar Libro</button>
                    </form>
                </div>
                
                <div class="form-group">
                    <h3>Devolver Libro</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="isbn_devolver">ISBN del Libro</label>
                            <input type="text" id="isbn_devolver" name="isbn_devolver" required>
                        </div>
                        <button type="submit" name="devolver_libro">Devolver Libro</button>
                    </form>
                </div>
            </div>
        </div>
        
        <h2 class="section-title">Inventario de Libros</h2>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Categoría</th>
                        <th>Año</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($libros)): ?>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td><?php echo $libro->getTitulo(); ?></td>
                                <td><?php echo $libro->getAutor(); ?></td>
                                <td><?php echo $libro->getIsbn(); ?></td>
                                <td><?php echo $libro->getCategoria(); ?></td>
                                <td><?php echo $libro->getAnioPublicacion(); ?></td>
                                <td class="<?php echo $libro->estaPrestado() ? 'status-prestado' : 'status-disponible'; ?>">
                                    <?php echo $libro->estaPrestado() ? 'Prestado' : 'Disponible'; ?>
                                </td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="isbn_eliminar" value="<?php echo $libro->getIsbn(); ?>">
                                        <button type="submit" name="eliminar_libro" class="btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No hay libros en el inventario.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>