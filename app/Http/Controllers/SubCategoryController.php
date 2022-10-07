<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCat = SubCategory::orderBy('sub_categories.name', 'ASC')
            ->where('sub_categories.deleted_at', null)
            ->join('categories', 'sub_categories.id_category', '=', 'categories.id')
            ->select('sub_categories.*', 'categories.name as name_category')
            ->get();
        return response()->json($subCat,200);
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
                'quantity'  =>  'required',
                'id_category'  =>  'required',
            ],[
                'name.required' =>  'Le nom est obligatoire',
                'quantity.required' =>  'La quantité est obligatoire',
                'id_category.required' =>  'Veuillez choisir une catégorie',
            ]);

            $subCat = new SubCategory(request()->name, request()->quantity, request()->id_category);
            if($subCat->save())
            {
                return response()->json($subCat, 200);
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
                'quantity'  =>  'required',
                'id_category'  =>  'required',
            ],[
                'name.required' =>  'Le nom est obligatoire',
                'quantity.required' =>  'La quantité est obligatoire',
                'id_category.required' =>  'Veuillez choisir une catégorie',
            ]);

            $subCat = SubCategory::find($id);
            $subCat->name = request()->name;
            $subCat->quantity = request()->quantity;
            $subCat->id_category = request()->id_category;

            if($subCat->update())
            {
                return response()->json($subCat,200);
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
            $subCat = SubCategory::find($id);
            if(!$subCat)
            {
                throw new ErrorException("Catégorie non valide");
            }

            if($subCat->delete())
            {
                return response()->json($subCat,200);
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
            $subCat = SubCategory::find($id);
            if(!$subCat)
            {
                throw new ErrorException("Catégorie non valide.");
            }

            if(!$subCat->trashed())
            {
                throw new ErrorException("Cette catégorie ne se trouve pas dans la corbeille");
            }

            if($subCat->restore())
            {
                return response()->json($subCat,200);
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
