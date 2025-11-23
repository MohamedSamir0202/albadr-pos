<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Settings\AdvancedSettings;
use App\Http\Controllers\Controller;

class AdvancedSettingsController extends Controller
{
    public function view(AdvancedSettings $advancedSettings)
    {
        return view('admin.settings.advanced')->with('advancedSettings' , $advancedSettings);
    }

    public function update(AdvancedSettings $advancedSettings, Request $request)
    {
        $advancedSettings->allow_decimal_quantities = $request->has('allow_decimal_quantities');

        $advancedSettings->default_discount_method = $request->default_discount_method;

        $advancedSettings->payment_methods = $request->input('payment_methods', []);

        $advancedSettings->save();

        return redirect()->route('admin.settings.advanced.view')
            ->with('success', 'Advanced settings updated successfully.');
    }
}
