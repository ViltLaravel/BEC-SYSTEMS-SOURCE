<?php

namespace App\Http\Controllers\Realtime;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RealtimeController extends Controller
{
    // Controller method to check for real-time updates
    public function checkRealTimeUpdates()
    {
        $warning = session('sweetAlertMessage');

        return response()->json(['warning' => $warning]);
    }

}
