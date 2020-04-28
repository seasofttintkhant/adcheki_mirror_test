<?php

namespace App\Http\Controllers\Backend;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateOperatorRequest;

class OperatorController extends Controller
{
    const INITIAL_ID = 1000000000;

    protected $page = 3;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operators = Admin::latest()->paginate($this->page);
        return view('admin.operators.index', compact('operators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.operators.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdateOperatorRequest $request)
    {
        Admin::create([
            'login_id' => $request->login_id,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'permitted_ip' => $request->permitted_ip
        ]);
        return redirect(route('operators.index'))->with('success', 'An operator is added.');
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
        $operator = Admin::findOrFail($id);
        return view('admin.operators.edit', compact('operator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrUpdateOperatorRequest $request, $id)
    {
        $operator = Admin::findOrFail($id);
        $operator->update([
            'login_id' => $request->login_id,
            'password' => $request->password ? bcrypt($request->password) : $operator->password,
            'role' => $request->role,
            'permitted_ip' => $request->permitted_ip
        ]);
        return redirect(route('operators.index'))->with('success', 'An operator is updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admin::destroy($id);
        return redirect(route('operators.index'))->with('success', 'An operator is removed.');
    }

    public function search(Request $request)
    {
        if ($request->role == 0) {
            $operators = Admin::latest()->paginate($this->page);
            $operators->appends(['role' => $request->role]);
            return view('admin.operators.index', compact('operators'));
        }
        $operators = Admin::where('role', $request->role)->latest()->paginate($this->page);
        $operators->appends(['role' => $request->role]);
        return view('admin.operators.index', compact('operators'));
    }
}
