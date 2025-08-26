<?php
class Libro {
    private $titulo;
    private $autor;
    private $isbn;
    private $categoria;
    private $anioPublicacion;
    private $prestado;
    private $usuarioPrestamo;
    private $fechaPrestamo;
    
    public function __construct($titulo, $autor, $isbn, $categoria, $anioPublicacion) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->isbn = $isbn;
        $this->categoria = $categoria;
        $this->anioPublicacion = $anioPublicacion;
        $this->prestado = false;
        $this->usuarioPrestamo = null;
        $this->fechaPrestamo = null;
    }
    
    // Getters
    public function getTitulo() {
        return $this->titulo;
    }
    
    public function getAutor() {
        return $this->autor;
    }
    
    public function getIsbn() {
        return $this->isbn;
    }
    
    public function getCategoria() {
        return $this->categoria;
    }
    
    public function getAnioPublicacion() {
        return $this->anioPublicacion;
    }
    
    public function estaPrestado() {
        return $this->prestado;
    }
    
    public function getUsuarioPrestamo() {
        return $this->usuarioPrestamo;
    }
    
    public function getFechaPrestamo() {
        return $this->fechaPrestamo;
    }
    
    // Setters
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    
    public function setAutor($autor) {
        $this->autor = $autor;
    }
    
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }
    
    public function setAnioPublicacion($anioPublicacion) {
        $this->anioPublicacion = $anioPublicacion;
    }
    
    // Métodos para préstamos
    public function prestar($usuario) {
        if (!$this->prestado) {
            $this->prestado = true;
            $this->usuarioPrestamo = $usuario;
            $this->fechaPrestamo = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
    
    public function devolver() {
        if ($this->prestado) {
            $this->prestado = false;
            $this->usuarioPrestamo = null;
            $this->fechaPrestamo = null;
            return true;
        }
        return false;
    }
}
?>