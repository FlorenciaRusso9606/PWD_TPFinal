<?php
require_once 'fpdf/fpdf.php';
class PDF extends FPDF
{
    public function generarPdfCompra($datos)
    {
        $this->AddPage();
        $this->agregarEncabezado();
        $this->agregaDatosCliente($datos);
        $this->agregarDatosProductos($datos);
        $this->agregarDatosEmpresa();
        return $this->mostrarPdf();
    }
    public function agregarEncabezado()
    {
        $this->SetTitle("Resumen de Compra");
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(100, 10, 'Resumen de compra', 0, 0, 'C');
        $this->Ln(10);
    }
    public function agregaDatosCliente($datos)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100, 10, 'Datos del cliente', 0, 0, 'L');
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        $this->Cell(100, 10, 'Nombre: ' . $datos['usnombre'], 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(100, 10, 'Email: ' . $datos['usmail'], 0, 0, 'L');
        $this->Ln(10);
    }
    public function agregarDatosProductos($datos)
    {
        $precioTotal = 0;
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100, 10, 'Productos:', 0, 0, 'L');
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        foreach ($datos['productos'] as $producto) {
            $this->Cell(100, 10, 'Producto: ' . $producto['pronombre'], 0, 0, 'L');
            $this->Ln(5);
            $this->Cell(100, 10, 'Precio: $' . $producto['proprecio'], 0, 0, 'L');
            $this->Ln(5);
            $precioTotal += $producto['precio'];
        }
        $this->Ln(10);
        $this->Cell(100, 10, 'Total:' . $precioTotal, 0, 0, 'L');
    }

    public function agregarDatosEmpresa()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100, 10, 'Datos de la empresa', 0, 0, 'L');
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        $this->Cell(100, 10, 'Nombre: Tienda Lenny', 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(100, 10, 'Grupo: 12', 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(100, 10, 'Materia: PWD', 0, 0, 'L');
        $this->Ln(10);
    }
    public function mostrarPdf()
    {
        return $this->Output('F', 'ResumenCompra.pdf');
    }
}
