<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent')->orWhere('parent', 0)->get();
        return view('dashboard.categories.add',compact('categories'));
    }



    public function getCategoriesDatatable()
    {
        $data = Category::select("*")->with('parents');

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action',function($row){
            return $btn =  '<a href="' . Route('category.edit', $row->id) . '"  class="edit btn btn-success btn-sm" ><i class="fa fa-edit"></i></a>
            <a id="deleteBtn" data-id="' . $row->id . '" class="edit btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
        })
        ->addColumn('title', function ($row) {
            return $row->translate(app()->getLocale())->title;
        })
        ->addColumn('parent', function ($row) {
            return ($row->parent ==  0) ? trans('words.main category') :   $row->parents->translate(app()->getLocale())->title;
        })
        /*->addColumn('user', function ($row) {
            return $row->user->show_name;
        })
        ->addColumn('status', function ($row) {
            return $row->status == 'published' ? 'منشور' :'مسودة' ;
        })
        ->addColumn('url', function ($row) {
            return '<a href="' . Route('news.post', [$row->id,$row->slug]).'" target="_blanck">الذهاب للمقالة</a>';
        })
        ->addColumn('url', function ($row) {
            return $row->create_at->toDateString();
        })*/
        ->rawColumns(['action','status','title'])
        ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = Category::create($request->except('image','_token'));
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = Str::uuid().$file->getClientOriginalName();
            $file->move(public_path('images'),$filename);
            $path = '/images/'.$filename;
            $category->update(['image'=>$path]);
        }

        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent')->orWhere('parent', 0)->get();
        return view('dashboard.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->except('image', '_token'));
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $path = 'images/' . $filename;

            $category->update(['image' => $path]);
        }
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete(Request $request)
    {
        if (is_numeric($request->id)) {
            Category::where('parent', $request->id)->delete();
            Category::where('id', $request->id)->delete();
        }

        return redirect()->route('category.index');
    }
}
