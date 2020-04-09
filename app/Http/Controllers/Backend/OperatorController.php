<?php

namespace App\Http\Controllers\Backend;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreOrUpdateOperatorRequest;

class OperatorController extends Controller
{
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
        $lastRecord = Admin::latest()->first();
        $nextId = 1;
        if ($lastRecord) {
            $nextId = $lastRecord->id + 1;
        }
        Admin::create([
            'operator_id' => '1' . str_pad($nextId, 9, '0', STR_PAD_LEFT),
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
     * @param  int  $operatorId
     * @return \Illuminate\Http\Response
     */
    public function edit($operatorId)
    {
        $operator = Admin::where('operator_id', $operatorId)->first();
        if (!$operator) {
            abort(404);
        }
        return view('admin.operators.edit', compact('operator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $operatorId
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrUpdateOperatorRequest $request, $operatorId)
    {
        $operator = Admin::where('operator_id', $operatorId)->first();
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
     * @param  int  $operatorId
     * @return \Illuminate\Http\Response
     */
    public function destroy($operatorId)
    {
        $operator = Admin::where('operator_id', $operatorId)->first();
        $operator->delete();
        return redirect(route('operators.index'))->with('success', 'An operator is remove.');
    }

    public function filterByRole(Request $request)
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
