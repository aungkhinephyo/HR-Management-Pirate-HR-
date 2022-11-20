<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = Auth::user();
        $biometrics = DB::table('webauthn_credentials')->where('user_id', $employee->id)->get();
        return view('app.profile.profile', compact('employee', 'biometrics'));
    }

    public function edit($id)
    {
        if ($id != Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }
        $employee = User::findOrFail($id);
        return view('app.profile.edit', compact('employee'));
    }

    public function update(UpdateProfile $request, $id)
    {
        $employee = User::findOrFail($id);

        $img_name = $employee->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete('employee/' . $employee->image);

            $img_file = $request->file('image');
            $img_name = uniqid() . '_' . $img_file->getClientOriginalName();
            Storage::disk('public')->put('employee/' . $img_name, file_get_contents($img_file));
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nrc_number' => $request->nrc_number,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'image' => $img_name,
        ];
        $employee->update($data);
        return redirect()->route('profile.index')->with('update', 'Your profile is successfully updated.');
    }

    public function changePassword()
    {
        return view('app.profile.change_password');
    }

    public function updatePassword(Request $request)
    {
        $this->passwordValidation($request);
        $user = Auth::user();
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        if (Hash::check($current_password, $user->password)) {
            if ($new_password === $confirm_password) {
                User::where('id', $user->id)->update([
                    'password' => Hash::make($confirm_password)
                ]);
            }
        } else {
            return back()->with(['current_password_wrong' => 'Seems like you forget your old password.']);
        }

        return redirect()->route('login')->with(['success' => 'Your password is successfully updated.']);
    }


    public function biometricData()
    {
        $employee = Auth::user();
        $biometrics = DB::table('webauthn_credentials')->where('user_id', $employee->id)->get();
        return view('app.components.biometric_data', compact('employee', 'biometrics'))->render();
    }

    private function passwordValidation($request)
    {
        $validationRules = [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ];
        Validator::make($request->all(), $validationRules)->validate();
    }
}
