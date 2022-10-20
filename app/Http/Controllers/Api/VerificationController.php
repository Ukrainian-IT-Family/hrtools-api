<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\ResendVerificationAction;
use App\Actions\Auth\VerificationAction;
use App\Actions\Auth\VerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends ApiController
{
    public function verify(
        $user_id,
        Request $validSignature,
        VerificationAction $action,
    ): JsonResponse {
        $verificationRequest = new VerificationRequest(
            (int) $user_id,
            $validSignature,
        );

        $action->execute($verificationRequest);

        return $this->successResponse(['msg' => __('register.user_successfully_verified')], 200);
    }

    public function resend(
        $user_id,
        ResendVerificationAction $action,
        Request $request,
    ) {
        $action->execute(
            new VerificationRequest(
                (int) $user_id,
                $request,
            )
        );

        return $this->successResponse(['msg' => __('register.email_verification_link_sent_on_your_email')]);
    }
}
