<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanySetting;
use Illuminate\Http\Request;
use App\Models\CompanySetting;

class CompanySettingController extends Controller
{
    public function show($id)
    {
        $this->checkPermission('view company setting');

        $setting = CompanySetting::findOrFail($id);
        return view('app.company_setting.show', compact('setting'));
    }

    public function edit($id)
    {
        $this->checkPermission('edit company setting');

        $setting = CompanySetting::findOrFail($id);
        return view('app.company_setting.edit', compact('setting'));
    }

    public function update(UpdateCompanySetting $request, $id)
    {
        $this->checkPermission('edit company setting');

        $setting = CompanySetting::findOrFail($id);
        $data = [
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'company_address' => $request->company_address,
            'office_start_time' => $request->office_start_time,
            'office_end_time' => $request->office_end_time,
            'break_start_time' => $request->break_start_time,
            'break_end_time' => $request->break_end_time,
        ];
        $setting->update($data);
        return redirect()->route('company_setting.show', $setting->id)->with('update', 'Company information is successfully updated.');
    }

    /* check user has permission */
    private function checkPermission($permission)
    {
        if (!auth()->user()->can($permission)) {
            return abort(403, 'Unauthorized action.');
        }
    }
}
