<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AuthRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Athenticatoion management class
 */
class AuthController extends Controller
{

    /**
     * Undocumented function
     *
     * @param AuthRequest $request
     * @param boolean $is_admin
     * @return Response
     */
    public function login(AuthRequest $request): Response
    {
        $credentials = $request->validated();
        $params = ['email' => $credentials['email'], 'password' => $credentials['password']];
        if (Auth::attempt($params)) {
            $token = $request->user()->createToken('app');
            $response = [
                'message' => 'Autenticado com sucesso',
                'token' => $token->plainTextToken,
                'expires_minutes' => config('sanctum.expiration'),
                'expires_in' => $this->createExpireTime(),
                'user' => auth()->user()
            ];

            return response($response);
        }

        return response(['message' => 'Acesso Não autorizado'], 401);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(["message" => "Logout realizado com sucesso"]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function fullLogout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(["message" => "Logout realizado com sucesso"]);
    }


    private function createExpireTime():string
    {
        $minutes = config('sanctum.expiration');
        $now = Carbon::now('America/Sao_Paulo');
        $now->addMinutes($minutes);
        return $now;
    }
}
