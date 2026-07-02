<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserController extends Controller
{
    public function index(): InertiaResponse
    {
        $users = User::query()
            ->latest()
            ->paginate(15)
            ->through(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at?->toDateTimeString(),
                'created_at' => $user->created_at?->toDateTimeString(),
                'is_current' => $user->id === request()->user()?->id,
            ]);

        return Inertia::render('admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // email_verified_at não é fillable; marca como verificado para permitir
        // acesso imediato ao painel (usuário criado internamente pelo admin).
        $user->forceFill(['email_verified_at' => now()])->save();

        return to_route('admin.users.index');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return to_route('admin.users.index');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()?->id) {
            throw ValidationException::withMessages([
                'user' => 'Você não pode excluir o próprio usuário.',
            ]);
        }

        if (User::query()->count() <= 1) {
            throw ValidationException::withMessages([
                'user' => 'Não é possível excluir o último usuário do sistema.',
            ]);
        }

        $user->delete();

        return to_route('admin.users.index');
    }
}
