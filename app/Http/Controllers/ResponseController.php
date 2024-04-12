<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Response;
use App\Models\Question;
use App\Models\Form;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreResponseRequest;
use App\Http\Requests\UpdateResponseRequest;
use App\Models\Allowed_domain;
use Illuminate\Validation\ValidationException;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Form $form)
    {
        $user = Auth::user();
        if ($form->creator_id !== $user->id) {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }
        
        $responses = Response::where('form_id', $form->id)->with('user', 'answers')->get();
        
        $formattedResponses = [];
        
        // Looping setiap respons
        foreach ($responses as $response) {
            $formattedAnswers = [];
    
            // Looping setiap jawaban dalam respons
            foreach ($response->answers as $answer) {    
                $formattedAnswers[$answer->question->name] = $answer->value;
            }
    
            // Buat data respons yang diformat
            $formattedResponse = [
                'date' => $response->created_at,
                'user' => [
                    'id' => $response->user->id,
                    'name' => $response->user->name,
                    'email' => $response->user->email,
                    'email_verified_at' => $response->user->email_verified_at,
                ],
                'answers' => $formattedAnswers,
            ];
    
            $formattedResponses[] = $formattedResponse;
        }
    
        return response()->json([
            'message' => 'Get responses success',
            'responses' => $formattedResponses,
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
    public function store(StoreResponseRequest $request, Form $form)
    {
        try {
            $validatedData = $request->validate([
                'answers' => 'required|array',
                'answers.*.question_id' => 'required',
                'answers.*.value' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $questionId = $request['answers.*.value']; 
                        $question = Question::find($questionId);
    
                        // Periksa apakah pertanyaan ditemukan dan is_required true
                        if ($question && $question->is_required && $request['answers.*.value']) {
                            $fail("The {$attribute} field is required because the question is required.");
                        }
                    }
                ]
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

        if ($form->limit_one_response) {
            $existingResponse = Response::where('user_id', $user->id)->where('form_id', $form->id)->exists();

            if ($existingResponse) {
                return response()->json([
                    'message' => 'You can not sumbit form twice'
                ], 422);
            }
        }

        $response = Response::create([
            'form_id' => $form->id,
            'user_id' => $user->id
        ]);

        foreach ($request['answers'] as $answerData) {
            Answer::create([
                'response_id' => $response->id,
                'question_id' => $answerData['question_id'],
                'value' =>  $answerData['value']
            ]);
        }

        return response()->json([
            'message' => 'Submit response success'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Response $response)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResponseRequest $request, Response $response)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Response $response)
    {
        //
    }
}
