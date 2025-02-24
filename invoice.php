<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invoice-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logo-placeholder {
            width: 150px;
            height: 150px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .item-row {
            margin-bottom: 10px;
        }
        .currency-select {
            width: 200px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="logo-placeholder" id="logoUpload">
                    + Add Your Logo
                </div>
                <input type="file" id="logoInput" hidden accept="image/*">
            </div>
            <div class="col-md-6 text-end">
                <h1>INVOICE</h1>
                <div class="mb-3">
                    <label>#</label>
                    <input type="text" class="form-control" id="invoiceNumber" value="1">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Who is this from?</label>
                    <input type="text" class="form-control" id="fromDetails">
                </div>
                <div class="mb-3">
                    <label>Bill To</label>
                    <input type="text" class="form-control" id="billTo">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Date</label>
                    <input type="date" class="form-control" id="invoiceDate">
                </div>
                <div class="mb-3">
                    <label>Payment Terms</label>
                    <input type="text" class="form-control" id="paymentTerms">
                </div>
                <div class="mb-3">
                    <label>Due Date</label>
                    <input type="date" class="form-control" id="dueDate">
                </div>
                <div class="mb-3">
                    <label>PO Number</label>
                    <input type="text" class="form-control" id="poNumber">
                </div>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table" id="invoiceItems">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <!-- Items will be added here dynamically -->
                </tbody>
            </table>
            <button class="btn btn-primary" id="addItem">+ Line Item</button>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Notes</label>
                    <textarea class="form-control" id="notes" rows="3" placeholder="Notes - any relevant information not already covered"></textarea>
                </div>
                <div class="mb-3">
                    <label>Terms</label>
                    <textarea class="form-control" id="terms" rows="3" placeholder="Terms and conditions - late fees, payment methods, delivery schedule"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4">Subtotal</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="subtotal" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4">Discount (%)</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="discount">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4">Tax (%)</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="tax">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4">Shipping</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="shipping">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4">Total</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="total" readonly>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4">Amount Paid</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="amountPaid">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4">Balance Due</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="balanceDue" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-end">
                <button class="btn btn-success" id="saveInvoice">Save Invoice</button>
                <button class="btn btn-primary" id="downloadInvoice">Download</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="invoice.js"></script>
</body>
</html>