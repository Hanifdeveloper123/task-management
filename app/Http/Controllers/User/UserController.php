<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Http\Controllers\AdminBaseController;
use App\Actions\Utility\Setting\GetSystemSettingMenuAction;

class UserController extends AdminBaseController
{
    public function __construct(
        GetSystemSettingMenuAction $getSystemSettingMenu
    ) {
        $this->getSystemSettingMenu = $getSystemSettingMenu;
    }

    public function userSettingIndex()
    {
        return Inertia::render($this->source . 'user/index', [
            "title" => 'Setting System Authentication',
            "additional" => [
                'menu' => $this->getSystemSettingMenu->handle()
            ]
        ]);
    }

}
