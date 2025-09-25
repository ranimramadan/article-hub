<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // توجيه ديناميكي حسب الدور/الصلاحية
        return redirect()->intended($this->redirectPathFor($request->user()));
    }

    protected function redirectPathFor($user): string
    {
        // لو أدمن، أو معه أي صلاحية إدارية → لوحة الأدمن
        if (
            $user->hasRole('admin')
            || $user->hasAnyPermission([
                'users.manage', 'roles.manage', 'permissions.manage', 'articles.review'
            ])
        ) {
            return route('admin.articles.pending');
        }

        // غير ذلك → لوحة المستخدم العادي
        return route('user.articles.index');
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
