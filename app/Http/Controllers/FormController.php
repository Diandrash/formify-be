<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Allowed_domain;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use App\Models\Question;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::all();
        return response()->json([
            'message' => 'Get All Forms Success',
            'forms' => $forms
        ], 200);
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
    public function store(StoreFormRequest $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:forms|regex:/^[a-zA-Z0-9.-]+$/|not_regex:/\s+/',
                'description' => 'nullable',
                'allowed_domains' => 'array',
                'limit_one_response' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $e->errors()
            ], 422);
        }

        $form = Form::create([
            'creator_id' => $request->user()->id,
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'description' => $validatedData['description'],
            'limit_one_response' => $validatedData['limit_one_response'],
        ]);

        foreach ($validatedData['allowed_domains'] as $domain) {
            Allowed_domain::create([
                'form_id' => $form->id,
                'domain' => $domain,
            ]);
        }

        return response()->json([
            'message' => 'Create Form Success',
            'form' => $form
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        $user = Auth::user();
        $userEmail = $user->email;
        $userEmailDomain = substr($userEmail, strpos($userEmail, '@') + 1);

        $allowedDomains = Allowed_domain::where('form_id', $form->id)->get();
        $isValidDomain = $allowedDomains->contains('domain', $userEmailDomain);

        if (!$isValidDomain) {
            return response()->json([
                'message' => 'Forbidden Access'
            ], 401);
        }

        $formId = $form->id;
        $formResults = Form::where('id', $formId)->with('allowedDomains', 'questions')->get();
        return response()->json([
            'message' => 'Get Form Success',
            'form' => $formResults
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        //
    }
}
