<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Datatables;
use App\User;
use App\TranslateTB;
use DB;

class PlayerController extends Controller
{
    public function __construct(){
        $this->user = new User;
        $this->translation = new TranslateTB;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('players.index',['top_players'=>$this->getTopPlayers()]);
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

    public function getBasicData()
    {
        $users = User::select(['username','email','created_at']);

        return Datatables::of($users)->make();
    }

    public function getTopPlayers(){
        $top = DB::select('select a.user_id, u.username, max(a.arcade_id) lvl, b.translated_word, b.guessed_word, b.total_score total_score
                            from dictionary.user_arcade_fact a 
                            left join dictionary.user_leaderboard_tb b on a.user_id = b.user_id 
                            join dictionary.users_tb u on a.user_id = u.user_id 
                            group by a.user_id, u.username ,b.translated_word, b.guessed_word, b.total_score
                            order by total_score desc,lvl  limit 7');
        return $top;
    }

    public function topDetails($id){
        return view('players.topDetails',[
            'user'=> $this->user->where('user_id',$id)->first()
        ]);
    }

    public function topData($id)
    {
        $details = $this->translation->select(['base_id','dialect_id','translated','sentiment','date'])->where('user_id',$id);

        return Datatables::of($details)->make();
    }


}
