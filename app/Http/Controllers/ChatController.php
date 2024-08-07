<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => 'Chat',
            'list' => Chat::join('users','users.user_id','=','chats.to')->where('to',Auth::user()->user_id)->get()
        ];

        return view('chat.index',$data);
    }

    public function frame()
    {
        $data = [
            'list' => Chat::select('chats.*','users.user_id','users.user_name')
            ->groupBy('from')
            ->join('users','users.user_id','=','chats.from')->where(function($query){
                $query->where('to',Auth::user()->user_id);
            })->orWhere(function($query){
                $query->where('from', Auth::user()->user_id);
            })->where('user_id','!=',Auth::user()->user_id)->get(),
            'listUser' => User::where('user_id','!=',Auth::user()->user_id)->get()
        ];
        return view('chat.index2',$data);
    }

    public function chat_message(Request $request)
    {
        $data = Chat::where(function($query) use ($request){
            $query->where('from', $request->from);
            $query->where('to',Auth::user()->user_id);
        })->orWhere(function($query) use ($request){
            $query->where('from', Auth::user()->user_id);
            $query->where('to',$request->from);
        })
        ->orderBy('created_at','asc')
        ->limit(25)
        ->get();
        return response()->json($data);
    }

    public function chat_send(Request $request)
    {
         // XSS Filtering & Sanitizing Input Data
         $request->merge(array_map('strip_tags', $request->all()));
         $request->merge(array_map('trim', $request->all()));

        Chat::create([
            'message' => $request->message,
            'from' => $request->from,
            'to' => $request->to,
            'isRead' => 0,
        ]);
    }

    public function chat_search_user(Request $request)
    {
        $data = User::where('user_id','LIKE','%'.$request->value.'%')
        ->orWhere('user_name','LIKE','%'.$request->value.'%')
        ->where('user_id','!=',Auth::user()->user_id)
        ->limit(25)
        ->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
}
