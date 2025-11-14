<?php
// File: services/EmailService.php

class EmailService {
    private $db;
    private $from_email = 'security@tomsworld.com.ph';
    private $from_name = 'Tom\'s World & Pan-Asia Security';
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Send QR code email to visitor
     */
    public function sendQRCodeEmail($visitor, $qr_code) {
        try {
            $subject = 'Your Visitor QR Code - Tom\'s World & Pan-Asia';
            
            $body = $this->getQREmailTemplate($visitor, $qr_code);
            
            // Queue email
            $stmt = $this->db->prepare("
                INSERT INTO email_queue 
                (recipient_email, recipient_name, subject, body, qr_code_attachment, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");
            
            $recipient_name = $visitor['first_name'] . ' ' . $visitor['last_name'];
            $stmt->execute([
                $visitor['email'],
                $recipient_name,
                $subject,
                $body,
                $qr_code
            ]);
            
            // Trigger email sending (in production, this would be a queue worker)
            $this->processEmailQueue();
            
            return true;
            
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send checkout email with QR code
     */
    public function sendCheckoutEmail($visit, $qr_code) {
        try {
            $subject = 'Thank You for Visiting - Your Express Check-In QR Code';
            
            $body = $this->getCheckoutEmailTemplate($visit, $qr_code);
            
            // Queue email
            $stmt = $this->db->prepare("
                INSERT INTO email_queue 
                (recipient_email, recipient_name, subject, body, qr_code_attachment, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");
            
            $recipient_name = $visit['first_name'] . ' ' . $visit['last_name'];
            $stmt->execute([
                $visit['email'],
                $recipient_name,
                $subject,
                $body,
                $qr_code
            ]);
            
            $this->processEmailQueue();
            
            return true;
            
        } catch (Exception $e) {
            error_log("Checkout email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get QR code email template
     */
    private function getQREmailTemplate($visitor, $qr_code) {
        $name = $visitor['first_name'] . ' ' . $visitor['last_name'];
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #f39c12, #1e9338); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 0 0 8px 8px; }
                .qr-box { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
                .qr-code { font-size: 24px; font-weight: bold; color: #f39c12; letter-spacing: 2px; }
                .button { display: inline-block; padding: 12px 30px; background: #f39c12; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
                .benefits { background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0; }
                .benefits ul { margin: 10px 0; padding-left: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Tom's World & Pan-Asia</h1>
                    <p>Visitor Management System</p>
                </div>
                
                <div class='content'>
                    <h2>Hello {$name}!</h2>
                    
                    <p>Thank you for registering with our Visitor Management System. We've generated a unique QR code for you to use on your future visits.</p>
                    
                    <div class='qr-box'>
                        <h3>Your Personal QR Code</h3>
                        <p class='qr-code'>{$qr_code}</p>
                        <p><small>Save this code for quick check-in</small></p>
                    </div>
                    
                    <div class='benefits'>
                        <h3>✨ Benefits of Using Your QR Code:</h3>
                        <ul>
                            <li>Express check-in - Skip the registration line</li>
                            <li>Automatic form filling - No need to re-enter your details</li>
                            <li>Faster badge printing - Get your visitor badge instantly</li>
                            <li>Visit history tracking - Keep track of all your visits</li>
                        </ul>
                    </div>
                    
                    <h3>How to Use:</h3>
                    <ol>
                        <li>Show this QR code at the reception desk</li>
                        <li>Our scanner will recognize you instantly</li>
                        <li>Select your host and purpose of visit</li>
                        <li>Receive your visitor badge and proceed</li>
                    </ol>
                    
                    <p style='text-align: center;'>
                        <a href='#' class='button'>Download QR Code Image</a>
                    </p>
                    
                    <div class='footer'>
                        <p><strong>Important:</strong> Keep this QR code secure and do not share it with others.</p>
                        <p>If you have any questions, please contact our security desk.</p>
                        <hr style='margin: 20px 0; border: none; border-top: 1px solid #ddd;'>
                        <p>Tom's World Philippines, Inc. | Pan-Asia International</p>
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $html;
    }
    
    /**
     * Get checkout email template
     */
    private function getCheckoutEmailTemplate($visit, $qr_code) {
        $name = $visit['first_name'] . ' ' . $visit['last_name'];
        $checkout_time = date('F j, Y g:i A');
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 0 0 8px 8px; }
                .visit-summary { background: #f0f9ff; padding: 15px; border-radius: 8px; margin: 20px 0; }
                .qr-box { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; border: 2px dashed #f39c12; }
                .qr-code { font-size: 24px; font-weight: bold; color: #f39c12; letter-spacing: 2px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Thank You for Visiting!</h1>
                    <p>Tom's World & Pan-Asia</p>
                </div>
                
                <div class='content'>
                    <h2>Dear {$name},</h2>
                    
                    <p>Thank you for visiting us today. Your visit has been successfully completed.</p>
                    
                    <div class='visit-summary'>
                        <h3>📋 Visit Summary</h3>
                        <p><strong>Badge Number:</strong> {$visit['badge_number']}</p>
                        <p><strong>Check-out Time:</strong> {$checkout_time}</p>
                        <p><strong>Company:</strong> {$visit['company']}</p>
                    </div>
                    
                    <div class='qr-box'>
                        <h3>🚀 Your Express Check-In QR Code</h3>
                        <p class='qr-code'>{$qr_code}</p>
                        <p><strong>Save this for your next visit!</strong></p>
                        <p><small>Simply show this code at reception for instant check-in</small></p>
                    </div>
                    
                    <p>We look forward to seeing you again. Have a great day!</p>
                    
                    <div class='footer'>
                        <p style='margin-top: 30px; text-align: center; color: #666;'>
                            Security Team<br>
                            Tom's World & Pan-Asia
                        </p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $html;
    }
    
    /**
     * Process email queue (simplified version)
     */
    private function processEmailQueue() {
        // In production, this would be a separate queue worker
        // For now, just mark as sent
        $stmt = $this->db->prepare("
            UPDATE email_queue 
            SET status = 'sent', sent_at = NOW(), attempts = attempts + 1
            WHERE status = 'pending'
            LIMIT 10
        ");
        $stmt->execute();
        
        // In production, actually send emails using PHPMailer or similar
        return true;
    }
}
?>