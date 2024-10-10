<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    // email otp
    public function sendEmail(Request $request) {
        // Validate the email input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email address',
            ], 400);
        }

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);
        // $otp = 555;

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'luisozius22@gmail.com'; // Your Gmail address
            $mail->Password = 'whublphhrmrvyokt'; // App-specific password or normal password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('luisozius22@gmail.com', 'aiDetect');
            $mail->addAddress($request->email); // Recipient email address

            // Content
            $mail->isHTML(false);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "Your OTP code is: $otp";

            $mail->send();

            // Return the OTP in the response for verification on the frontend
            return response()->json([
                'status' => 'success',
                'otp' => $otp, // Return the OTP to be verified on the frontend
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sending failed. Mailer Error: ' . $mail->ErrorInfo,
            ], 500);
        }
    }
}
