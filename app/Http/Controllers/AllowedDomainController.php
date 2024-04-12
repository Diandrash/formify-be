<?php

namespace App\Http\Controllers;

use App\Models\Allowed_domain;
use App\Http\Requests\StoreAllowed_domainRequest;
use App\Http\Requests\UpdateAllowed_domainRequest;

class AllowedDomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllowed_domainRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Allowed_domain $allowed_domain)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allowed_domain $allowed_domain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllowed_domainRequest $request, Allowed_domain $allowed_domain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allowed_domain $allowed_domain)
    {
        //
    }
}
