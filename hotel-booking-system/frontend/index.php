<?php
/**
 * Frontend entry — choose user or admin panel.
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStay — Frontend Panels</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #0a1628, #1a2744); color: #fff; }
        .panel-card { background: #fff; color: #1a2744; border-radius: 16px; padding: 2rem; text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3); transition: transform 0.2s; }
        .panel-card:hover { transform: translateY(-4px); }
        .panel-card i { font-size: 2.5rem; color: #c9a227; margin-bottom: 1rem; }
        .btn-gold { background: linear-gradient(135deg, #c9a227, #e8c547); color: #0a1628; font-weight: 600; border: none; }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold"><i class="fas fa-gem text-warning"></i> LuxeStay</h1>
            <p class="text-white-50">Hotel Booking Management System — Frontend</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <div class="panel-card h-100">
                    <i class="fas fa-user"></i>
                    <h4>User Panel</h4>
                    <p class="text-muted small mb-4">Browse hotels, book rooms, manage profile & payments</p>
                    <a href="user-panel/index.php" class="btn btn-gold w-100">Open User Panel</a>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel-card h-100">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Admin Panel</h4>
                    <p class="text-muted small mb-4">Dashboard, hotels, bookings, reports & more</p>
                    <a href="admin-panel/login.php" class="btn btn-gold w-100">Open Admin Panel</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
