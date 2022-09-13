<?php

namespace App\Http\Controllers;

use App\Section;
use Auth;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'section_name' => 'required|unique:sections|max:200',
            'description' => 'required'
        ],[
            'section_name.required' => 'يرجى إدخال اسم القسم',
            'section_name.unique' => 'القسم مسجل مسبقاً',
            'section_name.max' => 'لقد تجاوزت الحد من الكلمات',
            'description.required' => 'يرجى إدخال وصف القسم',
        ]);

            Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name
            ]);
            session()->flash('add_section', 'تمت الإضافة بنجاح');
            return redirect('/sections');

    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validate = $request->validate([
            'section_name' => 'required|unique:sections|max:200',
            'description' => 'required'
        ],[
            'section_name.required' => 'يرجى إدخال اسم القسم',
            'section_name.unique' => 'القسم مسجل مسبقاً',
            'section_name.max' => 'لقد تجاوزت الحد من الكلمات',
            'description.required' => 'يرجى إدخال وصف القسم',
        ]);

        $sections = Section::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('update_section_success','تم تعديل القسم بنجاح');
        return redirect('/sections');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;
        Section::find($id)->delete();
        session()->flash('delete_section','تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
