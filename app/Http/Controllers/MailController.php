<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\EmailVerificationMail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function index()
    {
        Mail::to('your_test_mail@gmail.com')->send(new EmailVerificationMail([
            'title' => 'The Title',
            'body' => 'The Body',
        ]));
    }
}
