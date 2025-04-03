<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Resources\UserCollection;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $validator->after(function ($validator) use ($request) {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                $validator->errors()->add('message', 'E-mail ou senha inválidos.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first('message'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Não autorizado!'], 401);
        }

        $user = User::where('email', $request->email)->first();

        $returnUserCollection = new UserCollection(collect([$user]));

        return [
            'message' => 'Login efetuado com sucesso!',
            'user' => $returnUserCollection->resource->first(),
            'token' => $token,
        ];
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'));
    }

    public function updateRegister(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado!'], Response::HTTP_NOT_FOUND);
        }

        $updateData = $request->all();

        $user->fill($updateData);

        if (!empty($updateData['password'])) {
            $user->password = bcrypt($updateData['password']);
        }

        if (!$user->save()) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Erro ao atualizar o usuário!');
        }

        return response()->json([
            'message' => 'Usuário atualizado com sucesso!',
            'data' => [
                'user' => $user,
            ],
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    public function delete(Request $request)
    {
        $user = $request->user();

        $user->delete();

        return response()->json([
            'message' => 'Usuário deletado com sucesso!',
        ], Response::HTTP_OK);
    }
}
