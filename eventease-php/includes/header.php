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
    
    <!-- Enterprise Favicon -->
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Crect width='512' height='512' rx='112' fill='%230f172a'/%3E%3Cpath fill='%2338bdf8' d='M144 144c-17.7 0-32 14.3-32 32v160c0 17.7 14.3 32 32 32h224c17.7 0 32-14.3 32-32V176c0-17.7-14.3-32-32-32H144zm0 32h224v160H144V176zm32 32v32h160v-32H176zm0 64v32h160v-32H176z'/%3E%3C/svg%3E">
    
    <!-- Bootswatch Zephyr Theme (Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/zephyr/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            letter-spacing: -0.025em;
            font-weight: 700;
        }
        
        main, .flex-grow {
            flex: 1;
        }

        /* Smooth Entrance Animations */
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            0% { opacity: 0; transform: translateY(-15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.98); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Apply Animations */
        .navbar {
            animation: fadeInDown 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) both;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .container {
            animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both;
        }
        .card {
            background-color: var(--ee-surface);
            border: 1px solid var(--ee-border);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            animation: popIn 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both;
        }
        
        /* Interactive Hover Effects */
        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            border-color: #cbd5e1;
        }
        
        .btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        .btn:active {
            transform: translateY(1px);
        }
        
        .table {
            border-color: var(--ee-border);
        }
        .table-hover tbody tr {
            transition: all 0.15s ease;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f5f9 !important;
        }
        
        /* Staggered animation delays for lists/cards */
        .row > div:nth-child(1) .card { animation-delay: 0.05s; }
        .row > div:nth-child(2) .card { animation-delay: 0.1s; }
        .row > div:nth-child(3) .card { animation-delay: 0.15s; }
        .row > div:nth-child(4) .card { animation-delay: 0.2s; }
        .row > div:nth-child(5) .card { animation-delay: 0.25s; }
        .row > div:nth-child(6) .card { animation-delay: 0.3s; }
    </style>
</head>
<body>

