<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Email;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailSearchRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreOrUpdateEmailRequest;

class EmailController extends Controller
{
    const INITIAL_ID = 1000000000;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = Email::latest('id')->with('contact.device')->paginate(10);
        return view('admin.emails.index', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.emails.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdateEmailRequest $request)
    {
        Email::create([
            'email' => $request->email,
            'is_valid' => $request->is_valid,
            'status' => $request->status,
        ]);

        return redirect(route('emails.index'))->with('success', 'An email is added.');
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
        $email = Email::findOrFail($id);
        return view('admin.emails.edit', compact('email'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrUpdateEmailRequest $request, $id)
    {
        $email = Email::findOrFail($id);
        $email->update([
            'email' => $request->email,
            'is_valid' => $request->is_valid,
            'status' => $request->status
        ]);

        return redirect(route('emails.index'))->with('success', 'An email is updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Email::destroy($id);
        return redirect(route('emails.index'))->with('success', 'An email is removed.');
    }

    public function search(EmailSearchRequest $request)
    {
        $emails = Email::where(function (Builder $query) use ($request) {
            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }
            if ($request->filled('is_valid_start') && $request->filled('is_valid_end')) {
                $query->whereBetween('is_valid', [
                    $request->is_valid_start,
                    $request->is_valid_end
                ]);
            } else {
                if ($request->filled('is_valid_start')) {
                    $query->where('is_valid', $request->is_valid_start);
                }
                if ($request->filled('is_valid_end')) {
                    $query->where('is_valid', $request->is_valid_end);
                }
            }
            if ($request->filled('status_start') && $request->fillled('status_end')) {
                $query->whereBetween('status', [
                    $request->status_start,
                    $request->status_end
                ]);
            } else {
                if ($request->filled('status_start')) {
                    $query->where('status', $request->status_start);
                }
                if ($request->filled('status_end')) {
                    $query->where('status', $request->status_end);
                }
            }
            if ($request->os != 0) {
                $query->whereHas('contact.device', function (Builder $query) use ($request) {
                    $query->where('os', $request->os);
                });
            }
            if ($request->anyFilled(['registration_start_date', 'registration_end_date'])) {
                $query->whereBetween('created_at', [
                    $this->convertStartDate($request->registration_start_date),
                    $this->convertEndDate($request->registration_end_date)
                ]);
            }
            if ($request->anyFilled(['update_start_date', 'update_end_date'])) {
                $query->whereBetween('updated_at', [
                    $this->convertStartDate($request->update_start_date),
                    $this->convertEndDate($request->update_end_date)
                ]);
            }
        })->with('contact.device')->paginate(10);
        $emails->appends([
            'registration_start_date' => $request->registration_start_date,
            'registration_end_date' => $request->registration_end_date,
            'update_start_date' => $request->update_start_date,
            'update_end_date' => $request->update_end_date,
            'email' => $request->email,
            'is_valid_start' => $request->is_valid_start,
            'is_valid_end' => $request->is_valid_end,
            'status_start' => $request->status_start,
            'status_end' => $request->status_end,
            'os' => $request->os
        ]);
        return view('admin.emails.index', compact('emails'));
    }

    protected function convertStartDate($startDate)
    {
        return Carbon::parse($startDate)->startOfDay()->toDateTimeString();
    }

    protected function convertEndDate($endDate)
    {
        return Carbon::parse($endDate)->addDay()->endOfDay()->toDateTimeString();
    }
}
