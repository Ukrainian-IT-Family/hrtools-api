<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Notifications\RegisterNewUserForAdminNotification;
use App\Repository\PhoneRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

final class RegisterAction
{
    public function __construct(private UserRepository $userRepository, private PhoneRepository $phoneRepository)
    {
    }

    public function execute(RegisterRequest $request): AuthenticationResponse
    {
        $user = $this->userRepository->create([
            'first_name' => $request->getFirstName(),
            'last_name' => $request->getLastName(),
            'email' => $request->getEmail(),
            'password' => Hash::make($request->getPassword()),
        ]);

        $phone = $this->phoneRepository->create([
            'phone' => $request->getPhone(),
            'user_id' => $user->getId(),
        ]);

        $user->sendEmailVerificationNotification();
        $token = auth()->login($user);

        $admins = $this->userRepository->getAdmins();
        Notification::send($admins, new RegisterNewUserForAdminNotification($user));

        return new AuthenticationResponse(
            $user,
            (string) $token,
            'bearer',
            auth()->factory()->getTTL() * 60
        );
    }
}
