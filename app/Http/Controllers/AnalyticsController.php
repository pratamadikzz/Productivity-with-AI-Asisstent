<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(
        Request $request,
        AnalyticsService $analyticsService
    ): View {

        $days = (int) $request->get('days', 7);

        if (! in_array($days, [7, 30, 90])) {
            $days = 7;
        }

        $analytics = $analyticsService->getOverview(
            auth()->user(),
            $days
        );

        return view(
            'analytics.index',
            $analytics
        );
    }
}