<?php

namespace App\Http\Controllers;

use App\Transaction;

use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;




class AccountActivity extends Controller
{


    /**
     * index
     * handel the table, pagination, sorting and searching,
     * this function get called from routes/api.php
     * 
     * 
     * @param  mixed $request
     *
     * @return void
     */
    public function index(Request $request)
    {

        $perPage = $request['perPage'];// get the how many result per page
        $page = $request['page'];// get the requested page
        $search = json_decode($request['search'])->searchTerm; // get the search term if there is one
        $sort = json_decode($request['sort']);// this object holds the type(asc or desc ) and the row that need to be sorted row 
        
        //check if searching and sorting at the same time
        if (strlen($search) > 0 && strlen($sort->field) > 1) return (is_numeric(substr($search, 0, 3)) ? (Transaction::orderBy($sort->field, $sort->type)->where('secondPartyCardNumber', 'LIKE', '%' . $search . '%')->paginate($perPage))
            : (Transaction::orderBy($sort->field, $sort->type)->where('secondPartyName', 'LIKE', '%' . $search . '%')->paginate($perPage)));

            // check if searching 
        else if (strlen($search) > 0) return
            is_numeric(substr($search, 0, 2)) ? (Transaction::where('secondPartyCardNumber', 'LIKE', '%' . $search . '%')->paginate($perPage))
            : (Transaction::where('secondPartyName', 'LIKE', '%' . $search . '%')->paginate($perPage));

             // check if sorting checked 
        else if (strlen($sort->field) > 1) return (Transaction::orderBy($sort->field, $sort->type)->paginate($perPage));
        // return a normal pagination
        else return Transaction::paginate($perPage);
        
    }

}
