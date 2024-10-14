<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    // email OTP
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
            $mail->setFrom('luisozius22@gmail.com', 'CypherSentinel');
            $mail->addAddress($request->email); // Recipient email address

            // Content
            $mail->isHTML(false);
            $mail->Subject = 'Verification';
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

    public function sendBulkEmail(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'emails' => 'required', // Validate as an array
            'emails.*' => 'required', // Validate each email
            'message' => 'required', // The message content
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the list of email addresses and the message from the request
        $emails = $request->emails;
        $messageContent = $request->message;

        // Variable to track failed emails
        $failedEmails = [];

        // Create a new PHPMailer instance
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

            // Set the sender's information
            $mail->setFrom('luisozius22@gmail.com', 'CypherSentinel');

            // Loop through each email address
            foreach ($emails as $email) {
                // Clear all recipients and add the new one
                $mail->clearAddresses();
                $mail->addAddress($email); // Add the recipient email address

                // Content
                $mail->isHTML(false);
                $mail->Subject = 'Notification';
                $mail->Body    = $messageContent;

                // Try sending the email
                try {
                    $mail->send();
                } catch (Exception $e) {
                    // If sending fails, store the failed email
                    $failedEmails[] = $email;
                }
            }

            // If any emails failed, return a partial success response
            if (count($failedEmails) > 0) {
                return response()->json([
                    'status' => 'partial_success',
                    'message' => 'Some emails failed to send',
                    'failed_emails' => $failedEmails, // Return the list of failed emails
                ], 207); // HTTP 207 Multi-Status for partial success
            }

            // All emails were sent successfully
            return response()->json([
                'status' => 'success',
                'message' => 'All emails sent successfully',
            ], 200);

        } catch (Exception $e) {
            // Catch any errors with the mail setup or sending
            return response()->json([
                'status' => 'error',
                'message' => 'Mail sending failed. Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // sms otp
    public function sendSms(Request $request)
    {
        // Validate the phone number input
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid phone number',
            ], 400);
        }

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Traccar SMS Gateway URL
        $traccarUrl = 'https://www.traccar.org/sms'; 

        // Prepare the data for the request body
        $postData = [
            'to' => $request->phone_number,
            'message' => "Your Verification Code is: '$otp'",
        ];

        try {
            // Send the request to the SMS gateway
            $response = Http::withHeaders([
                'Authorization' => 'c3IsmfL-QKijd_WEM2kzoA:APA91bFap0VxWNT1A3cYpldokHQ_RND_Pyb1MA4JQmnTRj2oqXM4szS7CVT6nWyfCQdtB6SivvI4F5DbUeD7EpA6C2pXBDeU85YoyFSAjFuR_Td_OW4kFFlwVrOMSrBnprxCO3lQp-dl' // Fetch API key from environment variables
            ])->post($traccarUrl, $postData);

            // Check if the SMS was sent successfully
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'otp' => $otp, // Return the OTP for frontend verification
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send SMS: ' . $response->body(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'SMS sending failed. Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // send bulk
    public function sendBulkSms(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'phone_numbers' => 'required|array', // Validate as an array
            'phone_numbers.*' => 'required', // Validate each phone number
            'message' => 'required', // The message must be a string
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the list of phone numbers and the message from the request
        $phoneNumbers = $request->phone_numbers;
        $message = $request->message;

        // Traccar SMS Gateway URL
        $traccarUrl = 'https://www.traccar.org/sms';

        // Variable to track failed numbers
        $failedNumbers = [];

        // Loop through each phone number
        foreach ($phoneNumbers as $phoneNumber) {
            // Prepare the data for the request body for each number
            $postData = [
                'to' => $phoneNumber,
                'message' => $message, 
            ];

            try {
                // Send the request to the SMS gateway
                $response = Http::withHeaders([
                    'Authorization' => 'c3IsmfL-QKijd_WEM2kzoA:APA91bFap0VxWNT1A3cYpldokHQ_RND_Pyb1MA4JQmnTRj2oqXM4szS7CVT6nWyfCQdtB6SivvI4F5DbUeD7EpA6C2pXBDeU85YoyFSAjFuR_Td_OW4kFFlwVrOMSrBnprxCO3lQp-dl', // Use environment variable for API key
                ])->post($traccarUrl, $postData);

                // Check if the SMS was sent successfully
                if (!$response->successful()) {
                    // If sending fails, store the failed number
                    $failedNumbers[] = $phoneNumber;
                }
            } catch (\Exception $e) {
                // If an exception occurs, store the failed number
                $failedNumbers[] = $phoneNumber;
            }
        }

        // If any numbers failed, return an error message
        if (count($failedNumbers) > 0) {
            return response()->json([
                'status' => 'partial_success',
                'message' => 'Some messages failed to send',
                'failed_numbers' => $failedNumbers, // Return the list of failed numbers
            ], 207); // HTTP 207 Multi-Status for partial success
        }

        // All messages were sent successfully
        return response()->json([
            'status' => 'success',
            'message' => 'All messages sent successfully',
        ], 200);
    }

}
