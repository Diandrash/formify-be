<?php

namespace App\Http\Controllers;

use App\Models\Allowed_domain;
use App\Models\Question;
use App\Models\Form;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
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
    public function store(StoreQuestionRequest $request, Form $form)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'choice_type' => 'required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes',
                'choices' => 'required_if:choice_type,multiple choice,dropdown,checkboxes',
                'is_required' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Invalid Field',
                'errors' => $e->errors()
            ], 422);
        }

        $user = Auth::user();
        $userEmail = $user->email;
        $userEmailDomain = substr($userEmail, strpos($userEmail, '@') + 1);

        $allowedDomains = Allowed_domain::where('form_id', $form->id)->get();

        if ($allowedDomains->isEmpty())  {
            $isValidDomain = true;
        } else {   
            $isValidDomain = $allowedDomains->contains('domain', $userEmailDomain);
        }

        if (!$isValidDomain) {
            return response()->json([
                'message' => 'Forbidden Access'
            ], 401);
        }

        $question = Question::create([
            'name' => $validatedData['name'],
            'choice_type' => $validatedData['choice_type'],
            'choices' => $validatedData['choices'],
            'form_id' => $form->id,
            'is_required' => $validatedData['is_required'],
        ]);

        return response()->json([
            'message' => 'Add question success',
            'question' => $question
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form, Question $question)
    {

        $user = Auth::user();
        $userEmail = $user->email;
        $userEmailDomain = substr($userEmail, strpos($userEmail, '@') + 1);

        $allowedDomains = Allowed_domain::where('form_id', $form->id)->get();
        if ($allowedDomains->isEmpty())  {
            $isValidDomain = true;
        } else {   
            $isValidDomain = $allowedDomains->contains('domain', $userEmailDomain);
        }

        if (!$isValidDomain) {
            return response()->json([
                'message' => 'Forbidden Access'
            ], 401);
        }

        $question->delete();
        return response()->json([
            'message' => 'Remove question success'
        ], 200);
    }
}
