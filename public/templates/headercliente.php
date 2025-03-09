<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        .dashboard-card {
            @apply bg-white border border-gray-200 rounded-xl shadow-lg;
        }
        
        .hover-transition {
            @apply transition-all duration-300 ease-in-out;
        }
        
        .dropdown-menu {
            display: none;
            animation: fadeIn 0.2s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');



        .float-wa {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            z-index: 100;
        }
                /* Estilos personalizados */
        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .step-indicator.active {
            background: #059669;
            color: white;
        }
        
        .product-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;  
        }
        
        .add-to-cart-btn {
            background: #059669;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        
        .add-to-cart-btn:hover {
            background: #047857;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .product-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            overflow: hidden;
            background-color: white;
            transition: transform 0.2s;
            max-width: 300px;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .image-container {
            height: 200px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9fafb;
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-height: 200px;
        }
        
        .add-to-cart-btn {
            background-color: #10b981;
            color: white;
            padding: 0.5rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }
        
        .add-to-cart-btn:hover {
            background-color: #059669;
        }
    </style>
    <title><?= $pageTitle ?></title>
</head>