<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // قائمة + بحث
    public function index(Request $request)
    {
        $q = User::query()->with('roles')->orderByDesc('id');

        if ($k = $request->query('q')) {
            $q->where(fn($qq) => $qq
                ->where('name', 'like', "%{$k}%")
                ->orWhere('email', 'like', "%{$k}%"));
        }

        $users = $q->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    // إنشاء: فورم
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    // تخزين مستخدم جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required','string','max:100'],
            'email'     => ['required','email','max:150','unique:users,email'],
            'password'  => ['nullable','string','min:8'],
            'roles'     => ['nullable','array'],
            'roles.*'   => ['integer','exists:roles,id'],
        ]);

        $user = new User();
        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password'] ?? str()->random(12));
        $user->save();

        if (!empty($data['roles'])) {
            $roles = Role::whereIn('id', $data['roles'])->get();
            $user->syncRoles($roles);
        }

        return redirect()->route('admin.users.index')->with('ok', 'User created');
    }

    // عرض بطاقة/تفاصيل 
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // تعديل: فورم
    public function edit(User $user)
    {
        $roles     = Role::orderBy('name')->get();
        $userRoles = $user->roles()->pluck('id')->all();
        return view('admin.users.edit', compact('user','roles','userRoles'));
    }

    // تحديث بيانات وأدوار
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required','string','max:100'],
            'email'     => ['required','email','max:150',"unique:users,email,{$user->id}"],
            'password'  => ['nullable','string','min:8'],
            'roles'     => ['nullable','array'],
            'roles.*'   => ['integer','exists:roles,id'],
        ]);

       
        if (isset($data['roles'])) {
            $newRoleIds = $data['roles'];
            $newRoleNames = Role::whereIn('id', $newRoleIds)->pluck('name')->all();
            $isRemovingAdmin = $user->hasRole('admin') && !in_array('admin', $newRoleNames, true);
            if ($isRemovingAdmin && $this->isLastAdmin($user)) {
                return back()->withErrors('The admin role cannot be removed from the last admin in the system.');
            }
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        if (isset($data['roles'])) {
            $roles = Role::whereIn('id', $data['roles'])->get();
            $user->syncRoles($roles);
        }

        return redirect()->route('admin.users.index')->with('ok', 'User updated');
    }

    // حذف مستخدم
    public function destroy(Request $request, User $user)
    {
        
        if ($request->user()->id === $user->id) {
            return back()->withErrors('You cant delete yourself.');
        }

        
        if ($user->hasRole('admin') && $this->isLastAdmin($user)) {
            return back()->withErrors('The last admin in the system cannot be deleted.');
        }

        $user->syncRoles([]); 
        $user->delete();

        return redirect()->route('admin.users.index')->with('ok', 'User deleted');
    }

    
    private function isLastAdmin(User $user): bool
    {
        if (! $user->hasRole('admin')) return false;
        return User::role('admin')->count() === 1;
    }
}
