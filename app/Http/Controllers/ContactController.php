<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function send(FormRequest $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'subject' => 'required|string|min:5',
            'message' => 'required|string|min:10',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $adminEmail = env('ADMIN_EMAIL', 'webmaster@sdarm.org');
        Mail::to($adminEmail)->send(new ContactMessage($validatedData));

        return back()->with([
            'flash' => [
                'banner' => 'Your message has been sent directly to the admin!',
                'bannerStyle' => 'success'
            ]
        ]);
    }
}
