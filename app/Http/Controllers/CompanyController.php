<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{

    // function require the data to create a company
    public function store(array $data)
    {   
        // declare the validator with them rules.
        $validator = Validator::make($data, [
            'company_name' => 'required|string|max:255',
            'principal_activity' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // if an validation error ocurred
        if ($validator->fails()) {
            // throw a ValidationException with $validator
            throw new ValidationException($validator);
        }

        // else, create a new Company model
        $newCompany = new Company();
        // assign properties
        // each property, will be the key of array
        // structure [key => value]
        $newCompany->company_name = $data['company_name'];
        $newCompany->principal_activity = $data['principal_activity'];
        $newCompany->user_id = $data['user_id'];
        // save the company
        $newCompany->save();

        // return the company created
        return $newCompany;
    }
}
