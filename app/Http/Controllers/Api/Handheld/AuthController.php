<?php

namespace App\Http\Controllers\Api\Handheld;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): array
    {
        $request->validate([
            'workspace_url' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['De angivna användaruppgifterna är felaktiga.'],
            ]);
        }

        $shop = Shop::where('url_alias', $request->workspace_url)->first();

        if (! $shop) {
            throw ValidationException::withMessages([
                'workspace_url' => ['Arbetsytan finns inte.'],
            ]);
        }

        $userBelongsToShop = $shop->users()->where('users.id', $user->id)->count() > 0;

        if (! $userBelongsToShop) {
            throw ValidationException::withMessages([
                'workspace_url' => ['Du har inte tillgång till arbetsytan.'],
            ]);
        }

        return ['token' => $user->createToken($request->device_name)->plainTextToken];
    }
}
