<?php
class Biblioteca {
    private $libros;
    
    public function __construct() {
        $this->libros = array();
    }
    
    public function agregarLibro(Libro $libro) {
        $isbn = $libro->getIsbn();
        if (!isset($this->libros[$isbn])) {
            $this->libros[$isbn] = $libro;
            return true;
        }
        return false;
    }
    
    public function eliminarLibro($isbn) {
        if (isset($this->libros[$isbn])) {
            unset($this->libros[$isbn]);
            return true;
        }
        return false;
    }
    
    public function buscarLibros($termino, $criterio = 'titulo') {
        $resultados = array();
        foreach ($this->libros as $libro) {
            $valor = '';
            switch ($criterio) {
                case 'titulo':
                    $valor = $libro->getTitulo();
                    break;
                case 'autor':
                    $valor = $libro->getAutor();
                    break;
                case 'categoria':
                    $valor = $libro->getCategoria();
                    break;
                case 'isbn':
                    $valor = $libro->getIsbn();
                    break;
                default:
                    $valor = $libro->getTitulo();
            }
            
            if (stripos($valor, $termino) !== false) {
                $resultados[] = $libro;
            }
        }
        return $resultados;
    }
    
    public function prestarLibro($isbn, $usuario) {
        if (isset($this->libros[$isbn])) {
            return $this->libros[$isbn]->prestar($usuario);
        }
        return false;
    }
    
    public function devolverLibro($isbn) {
        if (isset($this->libros[$isbn])) {
            return $this->libros[$isbn]->devolver();
        }
        return false;
    }
    
    public function getLibros() {
        return $this->libros;
    }
    
    public function getLibro($isbn) {
        return isset($this->libros[$isbn]) ? $this->libros[$isbn] : null;
    }
}
?>