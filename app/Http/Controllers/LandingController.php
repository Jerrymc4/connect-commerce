<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    /**
     * Display the landing page
     * 
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('central.landing.home', [
            'featuredStores' => $this->storeService->getActiveStores()->take(6),
        ]);
    }

    /**
     * Display about page
     * 
     * @return \Illuminate\View\View
     */
    public function about(): View
    {
        return view('central.landing.about');
    }

    /**
     * Display pricing page
     * 
     * @return \Illuminate\View\View
     */
    public function pricing(): View
    {
        return view('central.landing.pricing');
    }

    /**
     * Display contact page
     * 
     * @return \Illuminate\View\View
     */
    public function contact(): View
    {
        return view('central.landing.contact');
    }

    /**
     * Process contact form submission
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        
        // Here you would typically send an email or save to database
        
        return back()->with('success', 'Your message has been sent. We\'ll be in touch soon!');
    }
} 