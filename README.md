📟 Custom Invoice Generator

A lightweight, web-based invoice generator built with PHP, JavaScript, and TCPDF. Add your logo, client details, line items, and generate a professional PDF in seconds.

⚙️ Features

Drag & drop logo upload

Dynamic line items (Qty × Rate = Amount)

Real-time totals (Discount, Tax, Shipping)

PDF generation using TCPDF

Download or save invoices with unique filenames

🛠 Tech Stack

PHP (TCPDF)

HTML/CSS (Bootstrap 5)

JavaScript (jQuery)

📁 Folder Structure

invoices/
├── invoice.php          # Frontend UI
├── invoice.js           # Dynamic logic & calculations
├── save-invoice.php     # Generates & saves PDF
├── /tcpdf/              # TCPDF library
├── /invoices/           # Saved PDFs

🚀 Getting Started

Clone the repogit clone https://github.com/AbhaayB/Custom-Invoices.git

Serve with local PHP serverphp -S localhost:8000

Open in browserhttp://localhost:8000/invoice.php