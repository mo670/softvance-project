<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        try {
            $contact = DB::transaction(function () use ($validated) {
                return Contact::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'full_name' => $validated['first_name'].' '.$validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'company' => $validated['company'] ?? null,
                    'message' => $validated['message'],
                ]);
            });

            $mailSent = true;
            $adminEmail = config('mail.admin_email');
            if (is_string($adminEmail) && $adminEmail !== '') {
                try {
                    Mail::to($adminEmail)->send(new ContactFormMail($contact));
                } catch (\Throwable $e) {
                    $mailSent = false;
                    Log::error('Contact saved but email delivery failed: '.$e->getMessage());
                }
            }

            return response()->json([
                'success' => $mailSent,
                'message' => $mailSent
                    ? 'Message sent successfully!'
                    : 'Message saved, but email delivery failed. Please check mail configuration.',
                'data' => $contact,
                'mail_sent' => $mailSent,
            ], $mailSent ? 201 : 202);
        } catch (\Throwable $e) {
            Log::error('Contact form submission failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Could not save your message. Please try again later.',
                'data' => null,
            ], 500);
        }
    }
}
