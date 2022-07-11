<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;

class Home extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actionCollection = Action::all();
        $postCollection = Post::all();
        if(Auth::check()){
            return view('homepage',['postCollection'=>$postCollection,'actionCollection'=>$actionCollection]);
        }
        return view('login');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
        return User::create([
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function login(){
        return view('login');
    }

    public function loginAction(Request $request)
    {

        // return $request;

    $request->validate([
        'email'=>'required|email',
        'password'=>'required',
    ]);
    $email = $request->email;
    $password = $request->password;
    // $cred = $request->only('email','password');
    if(Auth::attempt(['email' => $email, 'password' => $password])){
        return redirect()->intended('homepage')->withSuccess('You are logged in');
    }
    return redirect('login')->withErrors('Invalid email or password');

    }

    public function register()
    {
       return view('registration');
    }

    public function registerAction(Request $request)
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ]);

        $data = $request->all();
        $createUser = $this->create($data);

        return redirect()->intended('homepage')->withSuccess('You are logged in');
    }
}
