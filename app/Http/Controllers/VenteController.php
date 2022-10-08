<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use Illuminate\Http\Request;
use App\Exceptions\ErrorException;
use App\Models\SubCategory;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventes = Vente::orderBy('ventes.created_at', 'DESC')
            ->where('ventes.deleted_at', null)
            ->join('sub_categories', 'ventes.id_sub_category', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.id_category', '=', 'categories.id')
            ->select('ventes.*', 'sub_categories.name as name_sub_ategory', 'categories.name as name_category')
            ->get();
        return response()->json($ventes,200);
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
                'amount'  =>  'required',
                'quantity'  =>  'required',
                'id_sub_category'  =>  'required',
            ],[
                'amount.required' =>  'Le montant est obligatoire',
                'quantity.required' =>  'La quantité est obligatoire',
                'id_sub_category.required' =>  'Veuillez choisir une catégorie',
            ]);

            $id = request()->id_sub_category;
            $subCat = SubCategory::find($id);
            $subCat->quantity = (int)($subCat->quantity) - request()->quantity;
            if($subCat->update())
            {
                $vente = new Vente(request()->amount, request()->quantity, request()->id_sub_category);
                if($vente->save())
                {
                    return response()->json($vente, 200);
                }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
