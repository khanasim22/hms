<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Models\Module;
use App\Models\Setting;
use App\Queries\ModuleDataTable;
use App\Repositories\SettingRepository;
use Flash;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SettingController extends AppBaseController
{
    /** @var SettingRepository */
    private $settingRepository;

    public function __construct(SettingRepository $settingRepo)
    {
        $this->settingRepository = $settingRepo;
    }

    public function edit(Request $request)
    {
        $settings = $this->settingRepository->getSyncList();
        $currencies = getCurrencies();
        $statusArr = Module::STATUS_ARR;
        $sectionName = ($request->section === null) ? 'general' : $request->section;

        return view("settings.$sectionName", compact('currencies', 'settings', 'statusArr', 'sectionName'));
    }

    public function update(UpdateSettingRequest $request)
    {
        $this->settingRepository->updateSetting($request->all());

        Flash::success(__('messages.settings').' '.__('messages.common.updated_successfully'));

        return redirect(route('settings.edit'));
    }

    public function getModule(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new ModuleDataTable())->get($request->only(['status'])))->make(true);
        }
    }

    public function activeDeactiveStatus(Module $module)
    {
        $is_active = ! $module->is_active;
        $module->update(['is_active' => $is_active]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function patientQueueThemeView()
    {
        $setting = getSettingValue();

        if (!isset($setting['patient_queue_theme'])) {
            $setting['patient_queue_theme'] = 0;
        }

        return view('settings.queue_theme', compact('setting'));
    }

    public function patientQueueThemeUpdate(Request $request)
    {
        $request->validate([
            'patient_queue_theme' => 'required',
        ]);

        $setting = Setting::where('key', '=', 'patient_queue_theme')->first();

        if ($setting) {
            $setting->update(['value' => $request->patient_queue_theme]);
        } else {
            Setting::create([
                'key' => 'patient_queue_theme',
                'value' => $request->patient_queue_theme,
            ]);
        }

        return $this->sendSuccess(__('messages.settings') . ' ' . __('messages.common.updated_successfully'));
    }

    public function settingsUploadThemeVideo(Request $request)
    {
        $request->validate([
            'patient_queue_theme_video' => 'required|file|mimes:mp4,webm,mov|max:20480',
        ]);

        if ($request->hasFile('patient_queue_theme_video')) {
            $setting = Setting::firstOrCreate(
                ['key' => 'patient_queue_theme_video']
            );
            $setting->clearMediaCollection(Setting::VIDEO_MEDIA_COLLECTION);
            $setting->addMedia($request->file('patient_queue_theme_video'))
                ->toMediaCollection(Setting::VIDEO_MEDIA_COLLECTION, config('app.media_disc'));
            $setting = $setting->refresh();

            $setting->update([
                'value' => $setting->getFirstMediaUrl(Setting::VIDEO_MEDIA_COLLECTION),
            ]);

        }

        return $this->sendSuccess(__('messages.settings') . ' ' . __('messages.common.updated_successfully'));
    }
}
