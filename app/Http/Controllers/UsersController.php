<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Helpers\MyException;
use Illuminate\Http\Request;
use Antare74\ResponseFormatter\Format;
use App\Http\Resources\UsersResource;

class UsersController extends Controller
{

    protected $table;

    public function __construct()
    {
        $this->table = (new Users)->getTable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Format::success(
                UsersResource::collection(
                    Users::filter(filter_request())
                        ->orderBy(filter_order($this->table), "desc")
                        ->paginate(
                            request()->input("limit", 10)
                        )
                ),
                "Data successfully retrieved",
                true
            );
        } catch (\Throwable $th) {
            return MyException::{__FUNCTION__}(__CLASS__, request()->all(), $th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string|max:255|unique:ads_categories,name",
                "description" => "string|max:255|nullable"
            ]);
            $Users = Users::create(request()->all());
            return Format::success(
                UsersResource::make($Users),
                "Data successfully created",
            );
        } catch (\Throwable $th) {
            return MyException::{__FUNCTION__}(__CLASS__, request()->all(), $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Users $Users)
    {
        try {
            return Format::success(
                UsersResource::make($Users),
                "Data successfully retrieved",
            );
        } catch (\Throwable $th) {
            return MyException::{__FUNCTION__}(__CLASS__, request()->all(), $th);
        }
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
    public function update(Request $request, Users $Users)
    {
        try {
            $request->validate([
                "name" => "required|string|max:255|unique:ads_categories,name,{$Users->id}",
                "description" => "string|max:255|nullable"
            ]);
            $Users->update(request()->all());
            return Format::success(
                UsersResource::make($Users),
                "Data successfully updated",
            );
        } catch (\Throwable $th) {
            return MyException::{__FUNCTION__}(__CLASS__, request()->all(), $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Users $Users)
    {
        try {
            $Users->delete();
            return Format::success(
                null,
                "Data successfully deleted",
            );
        } catch (\Throwable $th) {
            return MyException::{__FUNCTION__}(__CLASS__, request()->all(), $th);
        }
    }
}
