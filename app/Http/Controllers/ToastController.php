<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToastController extends Controller
{
    public function __invoke(Request $request)
    {
        $requestData = $request->all();
        $data = json_decode($requestData['ev'], true);
        return view('toasts.new_application', compact('data'));
    }
}
