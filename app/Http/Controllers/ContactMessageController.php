<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $contactReturnUrl = route(
    'portfolio.localized',
    [
        'locale' => in_array(
            app()->getLocale(),
            ['ar', 'en'],
            true
        ) ? app()->getLocale() : 'en',
    ]
) . '#contact-form';
        // Anti-spam honeypot
        if ($request->filled('website')) {
            return redirect()->to(url('/') . '#contact-form')
                ->with('contact_success', true);
        }

        // Validate visitor input
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'min:2', 'max:120',
            ],
            'email' => [
                'required', 'email:rfc', 'max:255',
            ],
            'subject' => [
                'required', 'string', 'min:3', 'max:180',
            ],
            'message' => [
                'required', 'string', 'min:10', 'max:5000',
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->to(url('/') . '#contact-form')
                ->withErrors($validator)
                ->withInput($request->only(
                    'name', 'email', 'subject', 'message'
                ));
        }

        // Store the message
        ContactMessage::create(array_merge(
            $validator->validated(),
            [
                'locale' => in_array(
                    app()->getLocale(),
                    ['ar', 'en'],
                    true
                ) ? app()->getLocale() : 'en',
            ]
        ));

        return redirect()->to($contactReturnUrl)
            ->with('contact_success', true);
    }
}