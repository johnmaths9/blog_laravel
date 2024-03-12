<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{

    public function index(){
        $setting = Setting::first();
        $this->authorize('view', $setting);
        return view('dashboard.settings');
    }

    public function update(Request $request, Setting $setting){
        //Setting::create($request->all());

        $data = [
            'logo'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'favicon'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'facebook'=>'nullable|string',
            'instagram'=>'nullable|string',
            'email'=>'nullable|email',
            'phone'=>'nullable|string',
        ];

        foreach (config('app.languages') as $key => $value) {
            $data[$key.'*.title'] = 'nullable|string';
            $data[$key.'*.content'] = 'nullable|string';
            $data[$key.'*.address'] = 'nullable|string';
         }

         //dd($data);

        $validatedData = $request->validate($data);

        $setting->update($request->except('logo','favicon','_token'));

        if ($request->has('logo')) {
            $file = $request->file('logo');
            $filename = Str::uuid().$file->getClientOriginalName();
            $file->move(public_path('images'),$filename);
            $path = '/images/'.$filename;
            $setting->update(['logo'=>$path]);
        }

        if ($request->has('favicon')) {
            $file = $request->file('favicon');
            $filename = Str::uuid().$file->getClientOriginalName();
            $file->move(public_path('images'),$filename);
            $path = '/images/'.$filename;
            $setting->update(['favicon'=>$path]);
        }






        return redirect()->route('dashboard.settings');
    }
}
