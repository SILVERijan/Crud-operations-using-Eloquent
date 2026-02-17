<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    /**
     * Display a listing of the user's forms.
     * Customers can only see their own forms.
     */
    public function index()
    {
        // Get only the authenticated user's forms
        $forms = Auth::user()->forms()->latest()->paginate(10);
        
        return view('customer.forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new form.
     */
    public function create()
    {
        return view('customer.forms.create');
    }

    /**
     * Store a newly created form in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Automatically assign the authenticated user as the owner
        $form = Auth::user()->forms()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => 'submitted',
        ]);

        return redirect()
            ->route('customer.forms.show', $form)
            ->with('success', 'Form submitted successfully!');
    }

    /**
     * Display the specified form.
     * Ownership is enforced by the 'owns' middleware.
     */
    public function show(Form $form)
    {
        return view('customer.forms.show', compact('form'));
    }

    /**
     * Remove the specified form from storage.
     * Customers can delete their own forms.
     * Ownership is enforced by the 'owns' middleware.
     */
    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()
            ->route('customer.forms.index')
            ->with('success', 'Form deleted successfully!');
    }
}
