<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }
    public function getData(DataTables $dataTables)
    {
        $categories = Category::query();
        return $dataTables->eloquent($categories)
            ->addColumn('action', function ($category) {
                return '
                    <button class="btn btn-warning btn-sm edit-btn" data-id="' . $category->id . '">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="' . $category->id . '">Delete</button>
                ';
            })
            ->toJson();
    }


    public function store(CategoryRequest $request)
    {
        Category::create($request->except('_token'));
        return response()->json(1);
    }

    public function update(CategoryRequest $request, $id)
    {
        Category::find($id)->update($request->except('_token'));
        return response()->json(1);
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
        return redirect()->route('categories.index');
    }
    public function show($id)
    {
        return response()->json(Category::find($id));
    }
}
