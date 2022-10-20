<?php

declare(strict_types=1);

namespace App\Actions\Status\Mail;

use App\Mail\TestMail;
use Mail;

class MailAction
{
    public function execute(MailRequest $request): void
    {
        Mail::to($request->getEmail())->send(
            new TestMail(
                $request->getMessage()
            )
        );
    }
}
