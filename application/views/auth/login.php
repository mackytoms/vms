<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN - KIOSK V-PASS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('<?= base_url("assets/images/bg/index_bg.jpg") ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-blend-mode: overlay;
            background-color: rgb(99 99 89 / 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            overflow: hidden;
        }

        .main-wrapper {
            width: 100%;
            max-width: 550px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
        }

        .card-container {
            border: 1px solid black;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            /* background: linear-gradient(135deg, #f39c12, #1e9338); */
            color: white;
            padding: 20px 30px;
            text-align: center;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.5em;
            font-weight: 600;
            color: #7f7f7f;
            /* -webkit-text-stroke: 0.5px black; */
        }

        .card-body {
            padding: 30px;
        }

        /* Portal Layout without VS */
        .vs-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: relative;
            min-height: 200px;
        }

        .portal-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 15px;
            position: relative;
        }

        .portal-side:hover {
            transform: scale(1.05);
        }

        .portal-side.pa-side {
            background: linear-gradient(135deg, rgba(30, 147, 56, 0.1), rgba(255, 215, 0, 0.1));
        }

        .portal-side.pa-side:hover {
            background: linear-gradient(135deg, rgba(30, 147, 56, 0.2), rgba(255, 215, 0, 0.2));
        }

        .portal-side.tw-side {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(230, 126, 34, 0.1));
        }

        .portal-side.tw-side:hover {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.2), rgba(230, 126, 34, 0.2));
        }

        .portal-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .portal-side:hover .portal-logo {
            transform: scale(1.1) rotate(5deg);
        }

        .portal-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            /* box-shadow: 0 5px 15px rgba(0,0,0,0.2); */
        }

        .portal-side h3 {
            margin: 0;
            font-size: 1.3em;
            font-weight: 700;
            color: #495057;
            transition: all 0.3s ease;
        }

        .portal-side.pa-side h3 {
            color: #1e9338;
        }

        .portal-side.tw-side h3 {
            color: #f39c12;
        }

        .portal-side:hover h3 {
            transform: translateY(-5px);
        }

        /* Swipe Up Hint */
        .swipe-hint {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9em;
            animation: bounce 2s ease-in-out infinite;
        }

        .swipe-hint i {
            display: block;
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Admin Card - Hidden by default */
        .admin-card {
            position: fixed;
            bottom: -100%;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 550px;
            transition: bottom 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 1000;
        }

        .admin-card.revealed {
            bottom: 20px;
        }

        /* Backdrop */
        .admin-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 999;
        }

        .admin-backdrop.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Transition Overlays */
        .transition-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .transition-overlay.active {
            display: flex;
            animation: fadeInScale 0.6s ease forwards;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0);
            }
            50% {
                opacity: 1;
                transform: scale(1.1);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .transition-overlay.pa-transition {
            background: linear-gradient(90deg, #1e9338a8 0%, #1e9338 100%, #1e9338a8 0%);
            
        }

        .transition-overlay.tw-transition {
            background: linear-gradient(90deg, #f39c12a8 0%, #f39c12 100%, #f39c12a8 0%);
        }

        .transition-content {
            text-align: center;
            color: white;
            animation: pulse 1s ease infinite;
        }

        .transition-content img {
            width: 50%;
            height: 50%;
            border-radius: 20%;
            margin-bottom: 20px;
            /* box-shadow: 0 10px 40px rgba(0,0,0,0.4); */
        }

        .transition-content h2 {
            font-size: 2.5em;
            font-weight: 700;
            -webkit-text-stroke: 1px black;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Admin Login Styles */
        .form-group { margin-bottom: 25px; }
        .form-label { color: #495057; font-weight: 500; margin-bottom: 8px; font-size: 0.95em; }

        .form-control {
            border: 2px solid #e1e5eb;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.1);
        }

        .input-group { position: relative; }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
            cursor: pointer;
            z-index: 10;
        }

        .form-control.with-icon { padding-right: 45px; }

        .btn-login {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 1.1em;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(243, 156, 18, 0.4);
            color: white;
        }

        .btn-toggle-login {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 1em;
            font-weight: 500;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-toggle-login:hover {
            background: linear-gradient(135deg, #5a6268, #3d4246);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
            color: white;
        }

        .btn-toggle-login i {
            transition: transform 0.3s ease;
        }

        .btn-toggle-login.active i:last-child {
            transform: rotate(180deg);
        }

        .login-form-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, opacity 0.3s ease, margin 0.3s ease;
            opacity: 0;
            margin-top: 0;
        }

        .login-form-container.show {
            max-height: 500px;
            opacity: 1;
            margin-top: 25px;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 20px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .alert-danger { background-color: #fee; color: #c33; }
        .alert-success { background-color: #d4edda; color: #155724; }

        .login-footer {
            text-align: center;
            padding: 20px;
            color: #95a5a6;
            font-size: 0.85em;
            background: white;
            border-radius: 0 0 20px 20px;
        }

        .spinner-border { display: none; width: 20px; height: 20px; margin-left: 10px; }
        .loading .spinner-border { display: inline-block; }
        .loading .btn-text { margin-right: 5px; }

        /* Close button for admin card */
        .admin-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .admin-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        @media (max-width: 480px) {
            .vs-container {
                flex-direction: column;
                gap: 20px;
                min-height: auto;
            }
            
            .card-body {
                padding: 20px;
            }

            .admin-card {
                width: calc(100% - 20px);
            }

            .admin-card.revealed {
                bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Backdrop -->
    <div class="admin-backdrop" id="adminBackdrop" onclick="hideAdminCard()"></div>

    <!-- Transition Overlays -->
    <div class="transition-overlay pa-transition" id="paTransition">
        <div class="transition-content">
            <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>"  style="border-radius: 50%;" alt="Pan-Asia">
            <h2>ENTERING PAN-ASIA PORTAL</h2>
        </div>
    </div>

    <div class="transition-overlay tw-transition" id="twTransition">
        <div class="transition-content">
            <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" alt="Toms World">
            <h2>ENTERING TOMS WORLD PORTAL</h2>
        </div>
    </div>

    <div class="main-wrapper">
        <!-- Visitor Access Card -->
        <div class="card-container">
            <div class="card-header">
                <h2>To Whom You'll Visit?</h2>
            </div>
            <div class="card-body">
                <div class="vs-container">
                    <!-- Pan-Asia Side (Left) -->
                    <div class="portal-side pa-side" onclick="navigateToPortal('pa')">
                        <div class="portal-logo">
                            <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>" alt="Pan-Asia">
                        </div>
                        <h3>PAN-ASIA</h3>
                    </div>

                    <!-- Toms World Side (Right) -->
                    <div class="portal-side tw-side" onclick="navigateToPortal('tw')">
                        <div class="portal-logo">
                            <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" alt="Toms World">
                        </div>
                        <h3>TOMS WORLD</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Swipe Up Hint -->
        <div class="swipe-hint">
            <i class="bi bi-chevron-up"></i>
            Swipe up for Admin Access
        </div>
    </div>

    <!-- Admin Login Card (Hidden) -->
    <div class="card-container admin-card" id="adminCard">
        <div class="card-header">
            <button class="admin-close" onclick="hideAdminCard()">
                <i class="bi bi-x-lg"></i>
            </button>
            <h2>Admin Access</h2>
        </div>
        <div class="card-body">
            <!-- Toggle Button -->
            <button type="button" class="btn-toggle-login" id="toggleLoginBtn">
                <i class="bi bi-lock"></i>
                <span>Admin Sign In</span>
                <i class="bi bi-chevron-down"></i>
            </button>

            <!-- Collapsible Login Form -->
            <div class="login-form-container" id="loginFormContainer">
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle"></i> <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>
                
                <form id="loginForm" method="POST" action="<?= base_url('auth/login') ?>">
                    <div class="form-group">
                        <label for="username" class="form-label">Username or Email</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control with-icon" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Enter your username"
                                   required>
                            <i class="bi bi-person input-icon"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control with-icon" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required>
                            <i class="bi bi-eye-slash input-icon" id="togglePassword"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-login" id="loginBtn">
                        <span class="btn-text">Sign In</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="login-footer">
            KIOSK V-PASS Copyright &copy; <?= date('Y') ?> All rights reserved.<br>
            Information Technology & Services Department.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Portal Navigation with Transition
        function navigateToPortal(portal) {
            const paTransition = document.getElementById('paTransition');
            const twTransition = document.getElementById('twTransition');
            
            if (portal === 'pa') {
                paTransition.classList.add('active');
                setTimeout(() => {
                    window.location.href = '<?= base_url('main/pa') ?>';
                }, 1200);
            } else if (portal === 'tw') {
                twTransition.classList.add('active');
                setTimeout(() => {
                    window.location.href = '<?= base_url('main/tw') ?>';
                }, 1200);
            }
        }

        // Swipe Detection
        let touchStartY = 0;
        let touchEndY = 0;
        const adminCard = document.getElementById('adminCard');
        const adminBackdrop = document.getElementById('adminBackdrop');
        let isAdminVisible = false;

        document.addEventListener('touchstart', function(e) {
            touchStartY = e.changedTouches[0].screenY;
        });

        document.addEventListener('touchend', function(e) {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        });

        // Also handle mouse swipe for desktop testing
        let mouseStartY = 0;
        let isMouseDown = false;

        document.addEventListener('mousedown', function(e) {
            mouseStartY = e.screenY;
            isMouseDown = true;
        });

        document.addEventListener('mouseup', function(e) {
            if (isMouseDown) {
                touchStartY = mouseStartY;
                touchEndY = e.screenY;
                handleSwipe();
                isMouseDown = false;
            }
        });

        function handleSwipe() {
            const swipeDistance = touchStartY - touchEndY;
            
            // Swipe up to reveal admin (at least 50px swipe)
            if (swipeDistance > 50 && !isAdminVisible) {
                showAdminCard();
            }
            // Swipe down to hide admin
            else if (swipeDistance < -50 && isAdminVisible) {
                hideAdminCard();
            }
        }

        function showAdminCard() {
            adminCard.classList.add('revealed');
            adminBackdrop.classList.add('active');
            isAdminVisible = true;
        }

        function hideAdminCard() {
            adminCard.classList.remove('revealed');
            adminBackdrop.classList.remove('active');
            isAdminVisible = false;
            
            // Close the form if it's open
            const loginFormContainer = document.getElementById('loginFormContainer');
            const toggleLoginBtn = document.getElementById('toggleLoginBtn');
            loginFormContainer.classList.remove('show');
            toggleLoginBtn.classList.remove('active');
        }

        // Toggle Login Form Visibility
        const toggleLoginBtn = document.getElementById('toggleLoginBtn');
        const loginFormContainer = document.getElementById('loginFormContainer');
        const usernameInput = document.getElementById('username');

        toggleLoginBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            loginFormContainer.classList.toggle('show');
            
            if (loginFormContainer.classList.contains('show')) {
                setTimeout(() => usernameInput.focus(), 400);
            }
        });

        // Auto-show admin card and form if there's an error or success message
        <?php if($this->session->flashdata('error') || $this->session->flashdata('success')): ?>
        showAdminCard();
        setTimeout(() => {
            toggleLoginBtn.classList.add('active');
            loginFormContainer.classList.add('show');
        }, 300);
        <?php endif; ?>

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
        
        // Form submission
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            setTimeout(() => { this.submit(); }, 500);
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'warning',
                title: 'Session Expired',
                text: 'You were logged out due to inactivity. Please log in again.',
                confirmButtonColor: '#f39c12'
            });
        });
    </script>
</body>
</html>