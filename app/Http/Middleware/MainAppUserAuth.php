<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\ExternalRequestService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MainAppUserAuth
{

    /**@var ExternalRequestService $externalRequestService */
    protected $externalRequestService;

    public function __construct()
    {
        $this->externalRequestService = resolve('ExternalRequestService');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userToken = $request->header('token');

        $user = User::where('token', $userToken)->whereDate('token_active_before', '<', now())->first();

        if (!$user) {
            $response = $this->externalRequestService->authUser($userToken);

            if ($response['status_code'] === JsonResponse::HTTP_UNAUTHORIZED) {
                return response()->json([], JsonResponse::HTTP_UNAUTHORIZED);
            } else {
                $validator = $this->validator((array) $response['body']);

                if ($validator->fails()) {
                    return response()->json(
                        ['messages' => $validator->getMessageBag()],
                        JsonResponse::HTTP_BAD_REQUEST
                    );
                } else {
                    User::updateOrCreate(['token' => $userToken], [
                        'email' => $response['body']->email,
                        'token' => $response['body']->token,
                        'role_id' => $response['body']->role_id,
                        'token_active_before' => now()->addHours(4)
                    ]);
                }
            }
        }

        return $next($request);
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'token' => 'required|string',
            'email' => 'required|string'
        ]);
    }
}
