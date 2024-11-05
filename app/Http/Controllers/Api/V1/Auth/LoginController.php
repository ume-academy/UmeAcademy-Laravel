<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\LoginInterface;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Exceptions\Auth\EmailNotVerifiedException;

class LoginController extends Controller
{
    public function __construct(
        private LoginInterface $login, 
    ) {}

    public function loginWithEmail(LoginRequest $request)
    {
        try {
            $data = $request->only(['email', 'password']);

            $token = $this->login->loginViaEmail($data);

            return $token;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Không thể tạo token.'], 500);
        } catch (InvalidCredentialsException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        } catch (EmailNotVerifiedException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

}
