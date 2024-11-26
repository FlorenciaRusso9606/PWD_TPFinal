<?php

class PDF extends FPDF
{
    public function generarPdfCompra($datos)
    {
        $this->SetMargins(15, 15, 15); // Margen de la página
        $this->AddPage();
        $this->agregarEncabezado();
        $this->agregarDatosCliente($datos);
        $this->agregarDatosProductos($datos);
        $this->agregarDatosEmpresa();
        return $this->mostrarPdf();
    }

    public function agregarEncabezado()
    {
        $this->SetFont('Arial', 'B', 20);
        $this->SetFillColor(63, 136, 197); // Fondo azul moderno
        $this->SetTextColor(255, 255, 255); // Texto blanco
        $this->Cell(0, 15, utf8_decode('Resumen de Compra'), 0, 1, 'C', true);
        $this->Ln(10);
    }

    public function agregarDatosCliente($datos)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(173, 216, 230); // Celeste claro
        $this->Cell(0, 8, utf8_decode('Datos del Cliente'), 0, 1, 'L', true);
        $this->Ln(3);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, utf8_decode('Nombre: ' . $datos['usnombre']), 0, 1, 'L');
        $this->Cell(0, 8, utf8_decode('Email: ' . $datos['usmail']), 0, 1, 'L');
        $this->Ln(5);
    }

    public function agregarDatosProductos($datos)
    {
        $precioTotal = 0;
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(63, 136, 197); // Fondo azul para cabecera
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 8, utf8_decode('Productos'), 0, 1, 'L', true);
        $this->Ln(3);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 200, 200); // Fondo gris claro
        $this->SetTextColor(0, 0, 0);
        $this->Cell(100, 8, utf8_decode('Producto'), 1, 0, 'C', true);
        $this->Cell(40, 8, utf8_decode('Cantidad'), 1, 0, 'C', true);
        $this->Cell(40, 8, utf8_decode('Precio'), 1, 1, 'C', true);

        $this->SetFont('Arial', '', 10);
        $this->SetFillColor(173, 216, 230); // Celeste claro para las celdas de los productos
        foreach ($datos['productos'] as $producto) {
            $this->Cell(100, 8, utf8_decode($producto['pronombre']), 1, 0, 'L', true);
            $this->Cell(40, 8, $producto['cantidad'], 1, 0, 'C');
            $this->Cell(40, 8, '$' . $producto['proprecio'], 1, 1, 'C', true);
            $precioTotal += $producto['proprecio'] * $producto['cantidad'];
        }
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(63, 136, 197); // Fondo azul
        $this->SetTextColor(255, 255, 255);
        $this->Cell(100, 8, '', 0, 0); // Espacio vacío
        $this->Cell(40, 8, 'Total:', 1, 0, 'C', true);
        $this->Cell(40, 8, '$' . $precioTotal, 1, 1, 'C', true);
        $this->Ln(10);
    }

    public function agregarDatosEmpresa()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(245, 245, 245); // Fondo gris claro
        $this->Cell(0, 8, utf8_decode('Datos de la Empresa'), 0, 1, 'L', true);
        $this->Ln(3);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, utf8_decode('Nombre: Tienda Lenny'), 0, 1, 'L');
        $this->Cell(0, 8, utf8_decode('Grupo: 12'), 0, 1, 'L');
        $this->Cell(0, 8, utf8_decode('Materia: PWD'), 0, 1, 'L');
        $this->Ln(10);
    }

    public function mostrarPdf()
    {
        $filePath = __DIR__ . '/../../ResumenCompra.pdf';
        $this->Output('F', $filePath); // Guardar en el servidor
        return $filePath;
    }
}
