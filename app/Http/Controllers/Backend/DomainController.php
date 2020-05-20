<?php

namespace App\Http\Controllers\Backend;

use App\Models\Domain;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateDomainRequest;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domain::latest()->paginate(10);
        return view('admin.domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.domains.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdateDomainRequest $request)
    {
        $dnsIp = $this->dnsIp($request->name);
        $isMatch = $this->isMatch($dnsIp, $request->ip);
        Domain::create([
            'name' => $request->name,
            'default_ip' => $request->ip,
            'dns_ip' => $dnsIp,
            'is_match' => $isMatch
        ]);

        return redirect(route('domains.index'))->with('success', 'The domain has been added.');
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
        $domain = Domain::findOrFail($id);
        return view('admin.domains.edit', compact('domain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrUpdateDomainRequest $request, $id)
    {
        $domain = Domain::findOrFail($id);
        $dnsIp = $this->dnsIp($request->name);
        $isMatch = $this->isMatch($dnsIp, $request->ip);
        $domain->update([
            'name' => $request->name,
            'default_ip' => $request->ip,
            'dns_ip' => $dnsIp,
            'is_match' => $isMatch
        ]);

        return redirect(route('domains.index'))->with('success', 'The domain has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Domain::destroy($id);
        return response()->json(['status' => 'success', 'message' => 'The domain has been removed.']);
    }

    protected function dnsIp($domainName)
    {
        $dnsRecord = dns_get_record($domainName, DNS_A);
        if ($dnsRecord) {
            return $dnsRecord[0]['ip'];
        }
        return null;
    }

    protected function isMatch($dnsIp, $ip)
    {
        return $dnsIp === $ip;
    }
}
