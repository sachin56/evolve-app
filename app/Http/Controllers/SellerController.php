<?php

namespace App\Http\Controllers;

use App\Models\seller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    public function index(){
        return view('seller');
    }
    public function create(){
        $result = seller::all();

        return DataTables($result)->make(true);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $result = new seller;
                $result->name = $request->name;
                $result->email = $request->email;

                $result->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New Seller']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
    }

    public function show($id){
        $result = seller::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $result = seller::find($request->id);
                $result->name = $request->name;
                $result->email = $request->email;

                $result->save();

                DB::commit();
                return response()->json(['db_success' => 'Seller Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){

        $result = seller::destroy($id);

        return response()->json($result);

    }
}
