<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventEase</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootswatch Zephyr Theme (Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/zephyr/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }
        main, .flex-grow {
            flex: 1;
        }
        
        /* Smooth Entrance Animations */
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Apply Animations */
        .navbar {
            animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .container {
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .card {
            border: none;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            animation: popIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        
        /* Interactive Hover Effects */
        .hover-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.12);
        }
        
        .btn {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .btn:active {
            transform: translateY(1px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }
        
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f3f5 !important;
            transform: scale(1.005);
        }
        
        /* Staggered animation delays for lists/cards */
        .row > div:nth-child(1) .card { animation-delay: 0.1s; }
        .row > div:nth-child(2) .card { animation-delay: 0.2s; }
        .row > div:nth-child(3) .card { animation-delay: 0.3s; }
        .row > div:nth-child(4) .card { animation-delay: 0.4s; }
        .row > div:nth-child(5) .card { animation-delay: 0.5s; }
        .row > div:nth-child(6) .card { animation-delay: 0.6s; }
    </style>
</head>
<body>
