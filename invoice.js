$(document).ready(function() {
    // Handle logo upload
    $('#logoUpload').click(function() {
        $('#logoInput').click();
    });

    $('#logoInput').change(function(e) {
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#logoUpload').html(`<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Add new item row
    $('#addItem').click(function() {
        addItemRow();
    });

    // Calculate totals when inputs change
    $(document).on('input', '.calculate-trigger', calculateTotals);

    function addItemRow() {
        const row = `
            <tr>
                <td><input type="text" class="form-control" placeholder="Description of item/service..."></td>
                <td><input type="number" class="form-control calculate-trigger quantity" value="1"></td>
                <td><input type="number" class="form-control calculate-trigger rate" value="0"></td>
                <td><input type="text" class="form-control amount" readonly></td>
            </tr>
        `;
        $('#itemsBody').append(row);
    }

    function calculateTotals() {
        let subtotal = 0;

        // Calculate each line item
        $('#itemsBody tr').each(function() {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const rate = parseFloat($(this).find('.rate').val()) || 0;
            const amount = quantity * rate;
            $(this).find('.amount').val(amount.toFixed(2));
            subtotal += amount;
        });

        $('#subtotal').val(subtotal.toFixed(2));

        const discount = parseFloat($('#discount').val()) || 0;
        const tax = parseFloat($('#tax').val()) || 0;
        const shipping = parseFloat($('#shipping').val()) || 0;

        const discountAmount = subtotal * (discount / 100);
        const taxAmount = subtotal * (tax / 100);
        const total = subtotal - discountAmount + taxAmount + shipping;

        $('#total').val(total.toFixed(2));

        const amountPaid = parseFloat($('#amountPaid').val()) || 0;
        const balanceDue = total - amountPaid;
        $('#balanceDue').val(balanceDue.toFixed(2));
    }

    // Save invoice
    $('#saveInvoice').click(function() {
        const invoiceData = {
            invoiceNumber: $('#invoiceNumber').val(),
            fromDetails: $('#fromDetails').val(),
            billTo: $('#billTo').val(),
            date: $('#invoiceDate').val(),
            paymentTerms: $('#paymentTerms').val(),
            dueDate: $('#dueDate').val(),
            poNumber: $('#poNumber').val(),
            items: [],
            notes: $('#notes').val(),
            terms: $('#terms').val(),
            subtotal: $('#subtotal').val(),
            discount: $('#discount').val(),
            tax: $('#tax').val(),
            shipping: $('#shipping').val(),
            total: $('#total').val(),
            amountPaid: $('#amountPaid').val(),
            balanceDue: $('#balanceDue').val()
        };

        $('#itemsBody tr').each(function() {
            invoiceData.items.push({
                description: $(this).find('td:eq(0) input').val(),
                quantity: $(this).find('td:eq(1) input').val(),
                rate: $(this).find('td:eq(2) input').val(),
                amount: $(this).find('td:eq(3) input').val()
            });
        });

        $.ajax({
            url: 'save_invoice.php',
            method: 'POST',
            data: invoiceData,
            success: function(response) {
                alert('Invoice saved successfully!');
            },
            error: function() {
                alert('Error saving invoice!');
            }
        });
    });
    // Add this to your existing JavaScript code

    $('#downloadInvoice').click(function() {
        // Get all form data
        const invoiceData = {
            invoiceNumber: $('#invoiceNumber').val(),
            currency: $('#currency').val(),
            fromDetails: $('#fromDetails').val(),
            billTo: $('#billTo').val(),
            date: $('#invoiceDate').val(),
            paymentTerms: $('#paymentTerms').val(),
            dueDate: $('#dueDate').val(),
            poNumber: $('#poNumber').val(),
            items: [],
            notes: $('#notes').val(),
            terms: $('#terms').val(),
            subtotal: $('#subtotal').val(),
            discount: $('#discount').val(),
            tax: $('#tax').val(),
            shipping: $('#shipping').val(),
            total: $('#total').val(),
            amountPaid: $('#amountPaid').val(),
            balanceDue: $('#balanceDue').val()
        };
    // Get logo if uploaded
    if ($('#logoUpload img').length > 0) {
        invoiceData.logo = $('#logoUpload img').attr('src');
    }

    // Get all items
    $('#itemsBody tr').each(function() {
        invoiceData.items.push({
            description: $(this).find('td:eq(0) input').val(),
            quantity: $(this).find('td:eq(1) input').val(),
            rate: $(this).find('td:eq(2) input').val(),
            amount: $(this).find('td:eq(3) input').val()
        });
    });

    // Generate PDF
    $.ajax({
        url: 'save_invoice.php',
        method: 'POST',
        data: invoiceData,
        success: function(response) {
            if (response.success) {
                // Create download link
                const link = document.createElement('a');
                link.href = response.filepath;
                link.download = response.filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('Error generating invoice: ' + response.message);
            }
        },
        error: function() {
            alert('Error generating invoice!');
        }
    });
    });
});