<?php

namespace App\Http\Controllers\Admin\Settings;

//use GeneralSettingsRequest;
use Illuminate\Http\Request;
use App\Settings\GeneralSettings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GeneralSettingsRequest;

class GeneralSettingsController extends Controller
{
    public function view(GeneralSettings $generalSettings)
    {
        return view('admin.settings.general')->with('generalSettings' , $generalSettings);
    }

    public function update(GeneralSettings $generalSettings, GeneralSettingsRequest $request)
    {
        $generalSettings->company_name = $request->company_name;
        $generalSettings->company_email = $request->company_email;
        $generalSettings->company_phone = $request->company_phone;
        //$generalSettings->company_logo = $request->company_logo;
        $generalSettings->save();

        return redirect()->route('admin.settings.general.view')
        ->with('success', 'General settings updated successfully.');
    }
}
