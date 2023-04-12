<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\ContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.pages.contact');
    }

    public function store(ContactRequest $request)
    {
        $validated = $request->validated();
        $contact = Contact::create($validated);

        // send notification to admins
        $notifiableUsers = User::role(['admin'])->get();
        Notification::send($notifiableUsers, new ContactNotification($contact));

        return redirect()->back()->with([
            "message" => __("app.contact_message_success"),
            "icon"    => "success",
            "timer"   => 6000,
        ]);
    }
}
