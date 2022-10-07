<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('name', 'ASC')->where('deleted_at', null)->get();
        return response()->json($categories,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            request()->validate([
                'name'  =>  'required',
            ],[
                'name.required' =>  'Le nom est obligatoire',
            ]);

            $category = new Category(request()->name);
            if($category->save())
            {
                return response()->json($category, 200);
            }
            throw new ErrorException("Erreur de traitement");
        }
        catch(ErrorException $e)
        {
            header("Erreur",true,422);
            return response()->json($e->getMessage(),422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            request()->validate([
                'name'  =>  'required',
            ],[
                'name.required' =>  'Le nom est obligatoire',
            ]);

            $category = Category::find($id);
            $category->name = request()->name;

            if($category->update())
            {
                return response()->json($category,200);
            }
            throw new ErrorException("Erreur de traitement.");
        }
        catch(ErrorException $e)
        {
            header("Erreur",true,422);
            return response()->json($e->getMessage(),422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $category = Category::find($id);
            if(!$category)
            {
                throw new ErrorException("Catégorie non valide");
            }

            if($category->delete())
            {
                return response()->json($category,200);
            }
            throw new ErrorException("Erreur de traitement.");
        }
        catch(ErrorException $e)
        {
            header("Erreur",true,422);
            return response()->json($e->getMessage(),422);
        }
    }

    /**
     * Restore a section
     * @param int id
     * @return Illuminate\Http\Response
     */
    public function restore($id)
    {
        try
        {
            $category = Category::find($id);
            if(!$category)
            {
                throw new ErrorException("Catégorie non valide.");
            }

            if(!$category->trashed())
            {
                throw new ErrorException("Cette catégorie ne se trouve pas dans la corbeille");
            }

            if($category->restore())
            {
                return response()->json($category,200);
            }
            throw new ErrorException("Erreur de traitement.");
        }
        catch(ErrorException $e)
        {
            header("Erreur",true,422);
            return response()->json($e->getMessage(),422);
        }
    }
}
