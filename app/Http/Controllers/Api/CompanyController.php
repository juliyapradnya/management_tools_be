<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Company;

class CompanyController extends Controller
{
    public function index(){
        $company = Company::all();

        if(count($company) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $company
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id){
        $company = Company::find($id);

        if(!is_null($company)){
            return response([
                'message' => 'Retrieve Company Success',
                'data' => $company
            ],200);
        }

        return response([
            'message' => 'Company Not Found',
            'data' => null
        ],400);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'company_name' => 'required',
            'company_alias' => 'required',
        
            
        ]);

        if($validate->fails())
            return response (['message' => $validate->errors()],400);

       $company = Company::create($storeData);
        return response([
            'message' => 'Add Company Success',
            'data' =>$company,
        ],200);
    }

    public function destroy($id){
        $company = Company::find($id);

        if(is_null($company)){
            return response([
                'message' => 'Company Not Found',
                'data' => null
            ],404);
        }

        if($company->delete()){
            return response([
                'message' => 'Delete Company Success',
                'data' => $company,
            ],200);
        }
        
        return response([
            'message' => 'Delete Company Failed',
            'data' => null,
        ],400);

    }

    public function update(Request $request, $id){
        $company = Company::find($id);
        if(is_null($company)){
            return response([
                'message' => 'Company Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'company_name' => 'required',
            'company_alias' => 'required',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $company->company_name = $updateData['company_name'];
        $company->company_alias = $updateData['company_alias'];
        

        if($company->save()){
            return response([
                'message' => 'Update Company Success',
                'data' => $company,
            ],200);
        }

        return response([
            'message' => 'Update Company Failed',
            'data' => null
        ],400);
    }
    
}
