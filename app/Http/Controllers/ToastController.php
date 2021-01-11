<?php

namespace App\Http\Controllers;

use App\Notifications\NewUserNotification;
use Illuminate\Http\Request;

class ToastController extends Controller
{
    public function __invoke(Request $request)
    {
        $requestData = $request->all();
        $data        = json_decode($requestData['ev'], true);

        if (array_key_exists('type', $data) && $data['type'] == NewUserNotification::class)
        {
            return view('toasts.new_user', compact('data'));
        }
        else
        {
            return view('toasts.new_application', compact('data'));
        }
    }
}
