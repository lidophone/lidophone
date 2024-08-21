<?php

namespace App\Http\Controllers;

use App\Enums\TrueFalse;
use App\Helpers\UisHelper;
use App\Services\DailyCompetition;
use Auth;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    public function enableExpertMode(): RedirectResponse
    {
        Gate::authorize('isAdmin');
        $user = Auth::user();
        $user->expert_mode = $user->expert_mode === TrueFalse::True->value
            ? TrueFalse::False->value
            : TrueFalse::True->value;
        $user->save();
        return redirect()->back();
    }

    public function dailyCompetition(DailyCompetition $dailyCompetition): View
    {
        return view('daily-competition', compact('dailyCompetition'));
    }

    public function eventTrigger(Request $request): void
    {
        $event = $request->get('event');
        $class = 'App\\Events\\' . $event;
        if (class_exists($class)) {
            $class::dispatch();
        }
    }

    public function phpinfo(): void
    {
        Gate::authorize('isAdmin');
        phpinfo();
    }

    public function uisCall(int $id): RedirectResponse
    {
        return redirect(UisHelper::getUrlToCall($id));
    }
}
