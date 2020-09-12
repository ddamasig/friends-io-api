<?php

namespace App\Http\Controllers;

use Core\Resources\NotificationResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Returns a collection of Notification instances
     */
    public function index(): JsonResource
    {
        return NotificationResource::collection(Auth::user()->notifications);
    }
}
