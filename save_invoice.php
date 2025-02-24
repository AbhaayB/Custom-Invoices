<?php
header('Content-Type: application/json');

try {
    $invoiceData = $_POST;
    
    // Generate PDF invoice
    require_once('tcpdf/tcpdf.php');

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Invoice System');
    $pdf->SetTitle('Invoice #' . $invoiceData['invoiceNumber']);
    
    // Remove header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Add a page
    $pdf->AddPage();
    
    // Check if logo exists and add it
    if (isset($invoiceData['logo']) && !empty($invoiceData['logo'])) {
        $logo = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $invoiceData['logo']));
        $tempFile = tempnam(sys_get_temp_dir(), 'logo');
        file_put_contents($tempFile, $logo);
        $pdf->Image($tempFile, 10, 10, 40);
        unlink($tempFile);
    }
    
    // Add invoice header
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 10, 'INVOICE', 0, 1, 'R');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Invoice #: ' . $invoiceData['invoiceNumber'], 0, 1, 'R');
    
    // Add company and billing details
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'From:', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, $invoiceData['fromDetails'], 0, 'L');
    
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Bill To:', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, $invoiceData['billTo'], 0, 'L');
    
    // Add invoice details
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(95, 10, 'Date: ' . $invoiceData['date'], 0, 0);
    $pdf->Cell(95, 10, 'Due Date: ' . $invoiceData['dueDate'], 0, 1);
    $pdf->Cell(95, 10, 'Payment Terms: ' . $invoiceData['paymentTerms'], 0, 0);
    $pdf->Cell(95, 10, 'PO Number: ' . $invoiceData['poNumber'], 0, 1);
    
    // Add items table
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(90, 10, 'Item', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'Rate', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'Amount', 1, 1, 'C', true);
    
    $pdf->SetFont('helvetica', '', 12);
    foreach ($invoiceData['items'] as $item) {
        $pdf->Cell(90, 10, $item['description'], 1);
        $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'C');
        $pdf->Cell(35, 10, $invoiceData['currency'] . ' ' . number_format($item['rate'], 2), 1, 0, 'R');
        $pdf->Cell(35, 10, $invoiceData['currency'] . ' ' . number_format($item['amount'], 2), 1, 1, 'R');
    }
    
    // Add totals
    $pdf->Ln(5);
    $pdf->Cell(120);
    $pdf->Cell(35, 10, 'Subtotal:', 0, 0, 'R');
    $pdf->Cell(35, 10, $invoiceData['currency'] . ' ' . number_format($invoiceData['subtotal'], 2), 0, 1, 'R');
    
    if ($invoiceData['discount'] > 0) {
        $pdf->Cell(120);
        $pdf->Cell(35, 10, 'Discount (' . $invoiceData['discount'] . '%):', 0, 0, 'R');
        $pdf->Cell(35, 10, $invoiceData['currency'] . ' ' . number_format($invoiceData['subtotal'] * $invoiceData['discount'] / 100, 2), 0, 1, 'R');
    }
    
    if ($invoiceData['tax'] > 0) {
        $pdf->Cell(120);
        $pdf->Cell(35, 10, 'Tax (' . $invoiceData['tax'] . '%):', 0, 0, 'R');
        $pdf->Cell(35, 10, $invoiceData['currency'] . ' ' . number_format($invoiceData['subtotal'] * $invoiceData['tax'] / 100, 2), 0, 1, 'R');
    }
    
    if ($invoiceData['shipping'] > 0) {
        $pdf->Cell(120);
        $pdf->Cell(35, 10, 'Shipping:', 0, 0, 'R');
        $pdf->Cell(35, 10, $invoiceData['currency'] . ' ' . number_format($invoiceData['shipping'], 2), 0, 1, 'R');
    }
    
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(120);
    $pdf->Cell(35, 10, 'Total:', 0, 0, 'R');
    $pdf->Cell(35, 10, $invoiceData['currency'] . ' ' . number_format($invoiceData['total'], 2), 0, 1, 'R');
    
    // Add notes and terms
    if (!empty($invoiceData['notes'])) {
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Notes:', 0, 1);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->MultiCell(0, 10, $invoiceData['notes'], 0, 'L');
    }
    
    if (!empty($invoiceData['terms'])) {
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Terms and Conditions:', 0, 1);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->MultiCell(0, 10, $invoiceData['terms'], 0, 'L');
    }
    
    // Generate unique filename
    $filename = 'invoice_' . $invoiceData['invoiceNumber'] . '_' . date('Y-m-d_H-i-s') . '.pdf';
    $filepath = 'invoices/' . $filename;
    
    // Create invoices directory if it doesn't exist
    if (!file_exists('invoices')) {
        mkdir('invoices', 0777, true);
    }
    
    // Save PDF
    $pdf->Output($filepath, 'F');
    
    echo json_encode([
        'success' => true,
        'message' => 'Invoice generated successfully',
        'filename' => $filename,
        'filepath' => $filepath
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>