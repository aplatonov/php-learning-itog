<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;

class SettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $this->authorize('admin-control');
        //dd ($user_id);
        $settings = Settings::firstOrCreate([]);

        return view('vendor.admin.edit-settings', ['settings'=>$settings]);        
    }

    public function store(Request $request)
    {
        $this->authorize('admin-control');
        $form = $request->all();

        $this->validate($request, [
            'settings_name' => 'nullable|max:150',
            'how_it_works_1' => 'nullable|max:5000',
            'how_it_works_2' => 'nullable|max:5000',
            'how_contact_us' => 'nullable|max:5000',
            'address' => 'nullable|max:150',
            'phone' => 'nullable|max:150',
            'email' => 'nullable|max:150',
            ]); 
        
        $settings = Settings::firstOrCreate([]);

        $settings->update($form);
       
        return redirect('/admin/settings')->with(['message' => 'Настройки обновлены']);
    }
}
