<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HandbookController;
use App\Http\Controllers\Api\HRController;
use App\Http\Controllers\Api\PollController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\VacationOrHospitalController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/status/{serviceName?}', [StatusController::class, 'status']);
Route::post('/mail', [StatusController::class, 'mail']);
Route::post('/broadcast', [StatusController::class, 'event']);

Route::prefix('v1')->group(function () {
    Route::middleware('localization')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AuthController::class, 'login'])->name('login');
            Route::post('register', [RegistrationController::class, 'register']);
            Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
            Route::post('reset', [AuthController::class, 'reset'])->name('reset-password');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });

        Route::prefix('email')->group(function () {
            Route::post('verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
            Route::post('resend/{id}', [VerificationController::class, 'resend'])->name('verification.resend');
        });

        Route::middleware('auth:api')->group(function () {
            Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
                Route::get('/workers', [AdminController::class, 'getWorkers']);

                Route::group(['prefix' => 'users'], function () {
                    Route::get('/', [AdminController::class, 'getAllUsers']);
                    Route::post('/search', [AdminController::class, 'getUsersByFullName']);
                    Route::put('/{id}', [AdminController::class, 'updateUser']);
                    Route::post('/{id}/resume', [AdminController::class, 'uploadUserResume']);
                    Route::delete('/{id}/resume', [AdminController::class, 'deleteUserResume']);
                    Route::delete('/{id}', [AdminController::class, 'deleteUser']);
                });

                Route::group(['prefix' => 'polls'], function () {
                    Route::get('/', [AdminController::class, 'getPollsForAdmin']);
                    Route::get('/latest', [AdminController::class, 'getLatestPollsForAdmin']);
                    Route::post('/', [HRController::class, 'createPoll']);
                    Route::get('/{id}', [HRController::class, 'getPoll']);
                    Route::put('/{id}', [HRController::class, 'editPoll']);
                    Route::delete('/{id}', [HRController::class, 'deletePoll']);
                    Route::post('/{id}/complete', [HRController::class, 'completePoll']);
                    Route::post('/{id}/send', [HRController::class, 'sendPoll']);
                });
                Route::get('/vacation', [AdminController::class, 'getVacationOrHospitalRequests']);
                Route::get('/vacation/latest', [AdminController::class, 'getLatestVacationOrHospitalRequests']);
                Route::post('/vacation/{id}/accept', [VacationOrHospitalController::class, 'accept']);
                Route::post('/vacation/{id}/cancel', [VacationOrHospitalController::class, 'cancel']);
            });
            Route::group(['middleware' => 'hr', 'prefix' => 'hr'], function () {
                Route::group(['prefix' => 'polls'], function () {
                    Route::get('/', [HRController::class, 'getPollsByAuthor']);
                    Route::get('/latest', [HRController::class, 'getLatestPollsByAuthor']);
                    Route::post('/', [HRController::class, 'createPoll']);
                    Route::get('/{id}', [HRController::class, 'getPoll']);
                    Route::put('/{id}', [HRController::class, 'editPoll']);
                    Route::delete('/{id}', [HRController::class, 'deletePoll']);
                    Route::post('/{id}/send', [HRController::class, 'sendPoll']);
                    Route::post('/{id}/complete', [HRController::class, 'completePoll']);
                });
                Route::get('/users', [HRController::class, 'getWorkersByHR']);
                Route::get('/vacation', [HRController::class, 'getWorkerVacationOrHospitalRequests']);
                Route::get('/vacation/latest', [HRController::class, 'getLatestWorkerVacationOrHospitalRequests']);
                Route::post('/vacation/{id}/accept', [VacationOrHospitalController::class, 'accept']);
                Route::post('/vacation/{id}/cancel', [VacationOrHospitalController::class, 'cancel']);
            });
            Route::group(['prefix' => 'vacation'], function () {
                Route::get('/', [VacationOrHospitalController::class, 'getCollectionForUser']);
                Route::get('/latest', [VacationOrHospitalController::class, 'getLatestForUser']);
                Route::get('/info', [VacationOrHospitalController::class, 'getVacationAndHospitalDays']);
                Route::post('/', [VacationOrHospitalController::class, 'createRequest']);
                Route::delete('/{id}', [VacationOrHospitalController::class, 'deleteRequest']);
            });
            Route::group(['prefix' => 'polls'], function () {
                Route::get('/', [PollController::class, 'getPollsForUser']);
                Route::get('/latest', [PollController::class, 'getLatestForUser']);
                Route::post('/{id}/do', [PollController::class, 'doPoll']);
                Route::get('/{id}/view', [PollController::class, 'viewPollResult']);
            });
            Route::group(['prefix' => 'role'], function () {
                Route::get('/', [RoleController::class, 'getRoles']);
                Route::get('/{id}', [RoleController::class, 'getRole']);
            });
            Route::group(['prefix' => '/marital-statuses'], function () {
                Route::get('/', [HandbookController::class, 'getMaritalStatuses']);
                Route::get('/{id}', [HandbookController::class, 'getMaritalStatus']);
            });
            Route::group(['prefix' => '/relationship'], function () {
                Route::get('/', [HandbookController::class, 'getRelationships']);
                Route::get('/{id}', [HandbookController::class, 'getRelationship']);
            });
            Route::group(['prefix' => '/question-type'], function () {
                Route::get('/', [HandbookController::class, 'getQuestionTypes']);
                Route::get('/{id}', [HandbookController::class, 'getQuestionType']);
            });
            Route::group(['prefix' => 'personal'], function () {
                Route::get('/me', [ProfileController::class, 'me']);
                Route::put('/me', [ProfileController::class, 'update']);
                Route::post('/resume', [ProfileController::class, 'uploadProfileResume']);
                Route::delete('/resume', [ProfileController::class, 'deleteProfileResume']);
                Route::post('/image', [ProfileController::class, 'uploadProfileImage']);
            });
        });
    });
});
