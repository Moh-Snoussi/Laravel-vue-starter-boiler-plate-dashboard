<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Account;
use App\Transaction;

class DashboardController extends Controller
{
    private $user;

    public function index(Request $request){

       return $user = Auth::user() ? $this->user = $user : response()->json([
        'errors' => [
            'message' => 'Unauthorized'
        ]
    ], 401);;

    }   
}
