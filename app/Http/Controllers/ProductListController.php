<?php

namespace App\Http\Controllers;

use App\Models\product_list;
use App\Models\seller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductListController extends Controller
{
    public function index(){

        $result = seller::all();
        
        return view('product_list')->with(['result'=>$result]);
    }

    public function create(){
        $result = product_list::all();

        return DataTables($result)->make(true);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'quntity' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $result = new product_list;
                $result->product_name = $request->product_name;
                $result->quntity = $request->quntity;
                $result->price = $request->price;
                $result->desciption = $request->description;
                $result->seller_id = $request->seller_id;

                $result->save();

                DB::commit();
                return response()->json(['db_success' => 'Added New product']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }
    }

    public function show($id){
        $result = product_list::find($id);

        return response()->json($result);

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'quntity' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_error' => $validator->errors()->all()]);
        }else{
            try{
                DB::beginTransaction();

                $result = product_list::find($request->id);
                $result->product_name = $request->product_name;
                $result->quntity = $request->quntity;
                $result->price = $request->price;
                $result->desciption = $request->description;

                $result->save();

                DB::commit();
                return response()->json(['db_success' => 'Product List Updated']);

            }catch(\Throwable $th){
                DB::rollback();
                throw $th;
                return response()->json(['db_error' =>'Database Error'.$th]);
            }

        }

    }

    public function destroy($id){

        $result = product_list::destroy($id);

        return response()->json($result);

    }

    public function seller_filter($id){

        $result = DB::table('product_lists')
                    ->where('product_lists.seller_id',$id)
                    ->select('product_lists.*')
                    ->get();
                    
        return response()->json($result);

    }
}
