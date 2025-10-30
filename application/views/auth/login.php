<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Visitor Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
       body {
            background-image: url('<?= base_url("assets/images/bg/index_bg.jpg") ?>');
            background-size: cover; /* makes sure the image covers the entire screen */
            background-position: center; /* centers the image */
            background-repeat: no-repeat; /* prevents tiling */

            /* Optional gradient overlay on top of the image */
            background-blend-mode: overlay;
            background-color: rgb(99 99 89 / 70%); /* semi-transparent gradient tone */

            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }


        .login-container {
            border: 1px solid black;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 550px;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #f39c12, #ecf3129e, #1e9338);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h1 {
            margin: 0;
            font-size: 2em;
            font-weight: 600;
            color: white; /* main text color */
            -webkit-text-stroke: 1px black; /* border thickness & color */
        }

        .login-header p {
            margin: 10px 0 0 0;
            opacity: 0.95;
            color: white;
            -webkit-text-stroke: 0.5px black;
        }

        .company-logos {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .logo-circle {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .logo-circle img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 50%;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

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

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
            cursor: pointer;
            z-index: 10;
        }

        .form-control.with-icon {
            padding-right: 45px;
        }

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
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .form-check-label {
            color: #6c757d;
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            color: #f39c12;
            text-decoration: none;
            font-size: 0.95em;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #e67e22;
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 20px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-danger {
            background-color: #fee;
            color: #c33;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .divider {
            text-align: center;
            margin: 30px 0 20px;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #95a5a6;
            position: relative;
        }

        .quick-access {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .quick-btn {
            flex: 1;
            padding: 10px;
            border: 2px solid #e1e5eb;
            background: white;
            border-radius: 10px;
            color: #495057;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 0.95em;
        }

        .quick-btn:hover {
            border-color: #f39c12;
            color: #f39c12;
            background: #fff9e6;
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            color: #95a5a6;
            font-size: 0.85em;
        }

        .spinner-border {
            display: none;
            width: 20px;
            height: 20px;
            margin-left: 10px;
        }

        .loading .spinner-border {
            display: inline-block;
        }

        .loading .btn-text {
            margin-right: 5px;
        }

        /* Mobile responsiveness */
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Visitor Management System</h1>
            <p>Secure Access Portal</p>
            <div class="company-logos">
                <div class="logo-circle">
                    <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" alt="Toms World">
                </div>
                <div class="logo-circle">
                    <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>" alt="Pan-Asia">
                </div>
            </div>
        </div>
        
        <div class="login-body">
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
                               required 
                               autofocus>
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
                
                <div class="remember-forgot">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="forgot-link" onclick="showForgotPassword()">Forgot Password?</a>
                </div>
                
                <button class="btn btn-login">
                    <a href="<?= base_url('main/admin') ?>">
                    
                            Sign In (Temporary)
                    </a>
                </button>
                
                
                <!-- <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button> -->
            </form>
            
            <div class="divider">
                <span>Quick Access</span>
            </div>
            
            <div class="quick-access">
                <a href="<?= base_url('main/tw') ?>" class="quick-btn">
                    <i class="bi bi-building"></i> TW Portal
                </a>
                <a href="<?= base_url('main/pa') ?>" class="quick-btn">
                    <i class="bi bi-globe"></i> PA Portal
                </a>
            </div>
        </div>
        
        <div class="login-footer">
            &copy; <?= date('Y') ?> TOMS WORLD. All rights reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
            
            // Add loading state
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            
            // Simulate login process (remove this and use actual form submission)
            setTimeout(() => {
                this.submit();
            }, 500);
        });
        
        // Forgot password
        function showForgotPassword() {
            Swal.fire({
                title: 'Reset Password',
                html: `
                    <p class="text-muted">Enter your email address and we'll send you instructions to reset your password.</p>
                    <input type="email" id="reset-email" class="swal2-input" placeholder="Enter your email">
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Reset Link',
                confirmButtonColor: '#f39c12',
                preConfirm: () => {
                    const email = document.getElementById('reset-email').value;
                    if (!email) {
                        Swal.showValidationMessage('Please enter your email address');
                    }
                    return email;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Sent!',
                        text: 'Password reset instructions have been sent to ' + result.value,
                        confirmButtonColor: '#f39c12'
                    });
                }
            });
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>