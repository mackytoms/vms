<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Check-In Kiosk - Toms World</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qr-creator/dist/qr-creator.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .kiosk-container {
            height: 100vh;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            background: white;
        }

        /* Header */
        .kiosk-header {
            background: linear-gradient(135deg, #f39c12a8 0%, #f39c12 100%);
            color: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .company-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .kiosk-header h1 {
            font-size: 2em;
            font-weight: 800;
            margin: 8px 0;
            letter-spacing: 1px;
        }

        .datetime-display {
            font-size: 1em;
            opacity: 0.9;
        }

        /* Main Content Area */
        .kiosk-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f8f9fa;
            position: relative;
            overflow-y: auto;
        }

        /* Step Indicator */
        .step-indicator {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .step-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #dee2e6;
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background: #f39c12;
            transform: scale(1.5);
        }

        .step-dot.completed {
            background: #27ae60;
        }

        /* Screen Containers */
        .screen {
            display: none;
            animation: slideIn 0.4s ease;
            width: 100%;
            max-width: 680px;
        }

        .screen.active {
            display: block;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Welcome Screen */
        .welcome-screen {
            text-align: center;
        }

        .welcome-icon {
            font-size: 80px;
            color: #f39c12;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        .welcome-message {
            font-size: 2.2em;
            color: #f39c12a8;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .welcome-submessage {
            font-size: 1.2em;
            color: #7f8c8d;
            margin-bottom: 25px;
        }

        .language-selector {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .language-btn {
            padding: 8px 20px;
            border: 2px solid #f39c12;
            background: white;
            color: #f39c12;
            border-radius: 20px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .language-btn:hover, .language-btn.active {
            background: #f39c12;
            color: white;
            transform: translateY(-2px);
        }

        /* Action Buttons */
        .action-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #f39c12;
        }

        .action-card i {
            font-size: 3em;
            margin-bottom: 15px;
            display: block;
        }

        .action-card h3 {
            font-size: 1.4em;
            margin-bottom: 8px;
            color: #f39c12a8;
        }

        .action-card p {
            color: #7f8c8d;
            font-size: 0.95em;
        }

        /* Form Styles */
        .form-screen {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .form-title {
            font-size: 1.8em;
            color: #f39c12a8;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 1.1em;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .form-control-lg, .form-select-lg {
            font-size: 1.2em;
            padding: 12px 15px;
            border-radius: 10px;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control-lg:focus, .form-select-lg:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
        }

        .form-control-lg.is-invalid, .form-select-lg.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: none;
            font-size: 0.875em;
            color: #dc3545;
            margin-top: 0.25rem;
        }

        .form-control-lg.is-invalid ~ .invalid-feedback,
        .form-select-lg.is-invalid ~ .invalid-feedback {
            display: block;
        }

        /* QR Scanner Styles */
        .qr-scanner-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        #qr-reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        #qr-reader video {
            border-radius: 10px;
        }

        .qr-upload-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #dee2e6;
        }

        .qr-upload-btn {
            position: relative;
            display: inline-block;
            padding: 12px 30px;
            background: white;
            border: 2px solid #f39c12;
            color: #f39c12;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qr-upload-btn:hover {
            background: #f39c12;
            color: white;
        }

        .qr-upload-btn input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        /* QR Code Display */
        .qr-code-display {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .qr-code-display canvas {
            margin: 0 auto;
        }

        /* Photo Capture */
        .photo-capture-container {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin: 15px 0;
        }

        .camera-view {
            width: 280px;
            height: 210px;
            background: #f39c12a8;
            margin: 0 auto 15px;
            border-radius: 12px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        #videoElement, #capturedImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        #capturedImage {
            display: none;
        }

        .camera-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 35%, rgba(0,0,0,0.3) 100%);
            pointer-events: none;
        }

        .face-guide {
            width: 150px;
            height: 180px;
            border: 3px dashed rgba(255,255,255,0.5);
            border-radius: 50%;
            position: absolute;
            top: 10%;
            left: 25%;
            transform: translate(-50%, -50%);
            animation: pulse 2s infinite;
        }

        /* Enhanced Host Selection */
        .department-selection {
            margin-bottom: 20px;
        }

        .employee-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            max-height: 300px;
            overflow-y: auto;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 15px;
        }

        .employee-card {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .employee-card:hover {
            border-color: #f39c12;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .employee-card.selected {
            background: #f39c12;
            color: white;
            border-color: #f39c12;
        }

        .employee-card i {
            font-size: 2.5em;
            margin-bottom: 8px;
            display: block;
        }

        .employee-card .employee-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .employee-card .employee-email {
            font-size: 0.85em;
            opacity: 0.7;
        }

        /* Purpose Selection */
        .purpose-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .purpose-card {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 20px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .purpose-card:hover {
            border-color: #f39c12;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .purpose-card.selected {
            background: #f39c12;
            color: white;
            border-color: #f39c12;
        }

        .purpose-card i {
            font-size: 2.5em;
            margin-bottom: 8px;
        }

        .purpose-card h5 {
            font-size: 0.95em;
            margin: 0;
        }

        /* Agreement Screen */
        .agreement-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 15px 0;
            max-height: 300px;
            overflow-y: auto;
        }

        .agreement-text {
            font-size: 1em;
            line-height: 1.6;
            color: #495057;
        }

        .agreement-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            margin: 15px 0;
            cursor: pointer;
        }

        .agreement-checkbox input[type="checkbox"] {
            width: 24px;
            height: 24px;
            cursor: pointer;
            margin-top: 3px;
        }

        .agreement-checkbox label {
            flex: 1;
            font-size: 1.05em;
            cursor: pointer;
        }

        /* Success Screen */
        .success-screen {
            text-align: center;
            padding: 30px;
            margin-top: 280px;
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: #27ae60;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon i {
            color: white;
            font-size: 60px;
        }

        .badge-preview {
            background: white;
            border: 3px solid #f39c12;
            border-radius: 12px;
            padding: 25px;
            max-width: 350px;
            margin: 25px auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .badge-photo-display {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 15px;
            border: 3px solid #f39c12;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-photo-display img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 15px;
        }

        .btn-large {
            padding: 15px 30px;
            font-size: 1.2em;
            border-radius: 10px;
            min-width: 150px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-back {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-back:hover {
            background: #d5dbde;
            transform: translateX(-3px);
        }

        .btn-next {
            background: linear-gradient(135deg, #f39c12, #f39c12a8);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .btn-next:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-skip {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-skip:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(127, 140, 141, 0.4);
        }

        .btn-print {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.4);
        }

        /* Loading Spinner */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #f39c12;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: white;
            font-size: 1.3em;
            margin-top: 15px;
        }

        /* Emergency Button */
        .emergency-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8em;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .emergency-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.5);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 15px;
        }

        .quick-action-btn {
            padding: 10px 20px;
            background: white;
            border: 2px solid #f39c12;
            color: #f39c12;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1em;
        }

        .quick-action-btn:hover {
            background: #f39c12;
            color: white;
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .booking-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .booking-item:hover {
            border-color: #f39c12;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .booking-code {
            font-family: monospace;
            font-size: 1.2em;
            font-weight: bold;
            color: #f39c12;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .badge-preview, .badge-preview * {
                visibility: visible;
            }
            .badge-preview {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
            }
        }
    </style>
</head>
<body>