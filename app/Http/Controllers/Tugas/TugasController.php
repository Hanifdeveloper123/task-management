<?php

namespace App\Http\Controllers\Tugas;

use Inertia\Inertia;
use App\Http\Controllers\AdminBaseController;
use App\Actions\Utility\Setting\GetSystemSettingMenuAction;







class TugasController extends AdminBaseController
{

    

  public function __construct(
        GetSystemSettingMenuAction $getSystemSettingMenu
    ) {
        $this->getSystemSettingMenu = $getSystemSettingMenu;
    }
    
    public function tugasSettingIndex()
    {
        return Inertia::render($this->source . 'tugas/index', [
            "title" => 'Setting System Authentication',
            "additional" => [
                'menu' => $this->getSystemSettingMenu->handle()
            ]
        ]);
    }

}
