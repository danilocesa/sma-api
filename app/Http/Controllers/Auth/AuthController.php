<?php

namespace App\Http\Controllers\Auth;

use App\Dialects;
use App\User;
use App\BaseTB;
use App\TranslateTB;
use App\ArcadesTB;
use App\UserArcadeTB;
use App\LeaderBoardTB;
use Validator;
use Hash;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    protected $redirectTo = '/home';
    protected $loginPath = '/login';
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->user = new User;
        $this->dialect = new Dialects;
        $this->translateTB = new TranslateTB;
        $this->userArcade = new UserArcadeTB;
        $this->arcadeTB = new ArcadesTB;
        $this->leaderboard = new LeaderBoardTB;
    }
    public function showLoginForm(){
        abort(404);
    }
    public function showRegistrationForm(){

       abort(404);
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(),  [
            'username' => 'required|min:3',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'email'    => 'required|email',
            'dialect'  => 'required',
            'terms_con' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }else{
            $checkUser = $this->user->where(['username'=>$request->username])->first();
            if($checkUser){
                return response()->json('exists');   
            }else{
                $this->user->username = $request->username;
                $this->user->password = bcrypt($request->password);
                $this->user->email = $request->email;
                $this->user->dialect = $request->dialect;
                $this->user->save();
                return response()->json('success');   
            }
        }
    }
    public function login(Request $request){
        $userNameCheck = $this->user->where(['username'=>$request->username])->first();
        if($userNameCheck){
            if (Hash::check($request->password, $userNameCheck->password)) {
                $request->session()->put('logged', 1);
                $request->session()->put('userSession', array(
                    'user_id'=>$userNameCheck->user_id
                    ,'username'=>$request->username
                    ,'email'=>$userNameCheck->email
                    ,'dialect'=>$userNameCheck->dialect
                ));
                return response()->json([
                    'response'=>'success'
                ]);
            } 
            else{
                return response()->json('incorrect');
            }
        } else{
            return response()->json('notExist');  
        }
    }
    public function getToken(){
        return response()->json(csrf_token());
    }
    public function getDialects(Request $request){
        return response()->json($this->dialect->all());
    }
    public function getRandomText(Request $request){
        if ($request->session()->has('logged')){
            $basetb = \App\BaseTB::orderByRaw('RANDOM()')->first();
            $synsetOne = str_replace('#','',str_replace('_',' ',explode(",", substr($basetb->synset_terms, 1, -1))));
            $arrayRand = [];
            $arrayRand['base_id'] = $basetb->base_id;
            $arrayRand['synset_terms'] =  substr( strtoupper($synsetOne[0]), 0, -2);
            $arrayRand['gloss'] = $basetb->gloss;
            $arrayRand['pos'] = $basetb->pos;
            $arrayRand['pos_score'] = $basetb->pos_score;
            $arrayRand['neg_score'] = $basetb->neg_score;
            return response()->json($arrayRand);
        }    
        else{
            abort(403, 'Unauthorized action.');
        }         
    }
    public function saveTranslate(Request $request) {
        if ($request->session()->has('logged')){
            $leaderboard = $this->leaderboard->where('user_id',$request->session()->get('userSession')['user_id'])->first();

            if($request->translated == 1){ //Insert translated
                if(count($leaderboard) < 1){ //Insert
                    $this->leaderboard->user_id = $request->session()->get('userSession')['user_id'];
                    $this->leaderboard->translated_word = 1;
                    $this->leaderboard->save();
                } else { // Update
                    $this->leaderboard->where(['user_id'=>$request->session()->get('userSession')['user_id']])->update(['translated_word'=>$leaderboard->translated_word + 1]);
                }
            }
            if($request->uaTB == 1){
               $this->userArcade->user_id =  $request->session()->get('userSession')['user_id'];
               $this->userArcade->arcade_id =  $request->aLevel;
               $this->userArcade->save();
            }
            $this->translateTB->base_id = $request->base_id;
            $this->translateTB->dialect_id = $request->session()->get('userSession')['dialect'];
            $this->translateTB->user_id = $request->session()->get('userSession')['user_id'];
            $this->translateTB->translated = $request->translated;
            $this->translateTB->sentiment = $request->sentiment;
            $this->translateTB->date = date('Y-m-d H:i:s');
            $this->translateTB->save();
            return response()->json('success'); 
        }
        else{
            abort(403, 'Unauthorized action.');
        }
    }

    function userInfo(Request $request,$info){
        $userId = $request->session()->get('userSession')['user_id'];
        $userInfo = '';
        switch ($info) {
            case 'arcade':
                $userArcade = $this->userArcade->where(['user_id'=>$userId])->first(); //Check user arcade info
                $userDetails = [];
                if($userArcade){ //Existing in tb
                    $userArcade = $this->userArcade->where(['user_id'=>$userId])->orderBy('arcade_id','desc')->first();
                    $levelno = $userArcade->arcade_id + 1;
                    $arcadeDB = $this->arcadeTB->where('arcade_level',$levelno)->first();
                    $translateText = $this->getTranslateText($arcadeDB->char_limit);
                    $shuffled = str_shuffle($translateText['synset_terms']);
                    $userDetails['level_name'] = 'Level '.$levelno;
                    $userDetails['arcade_id'] = $levelno;
                    $userDetails['arcade_map'] = $arcadeDB->arcade_map;
                    $userDetails['jumble_words'] = str_shuffle($translateText['synset_terms']);
                    $userDetails['base_id'] = $translateText['base_id'];
                    $userDetails['synset_terms'] = $translateText['synset_terms'];
                    $userDetails['gloss'] = $translateText['gloss'];
                    $userInfo = $userDetails;
                } else{
                    $arcadeDB = $this->arcadeTB->where('arcade_level',1)->first();
                    $translateText = $this->getTranslateText(4);
                    $userDetails['level_name'] = 'Level 1';
                    $userDetails['arcade_id'] = 1;
                    $userDetails['arcade_map'] = $arcadeDB->arcade_map;
                    $userDetails['jumble_words'] = str_shuffle($translateText['synset_terms']);
                    $userDetails['base_id'] = $translateText['base_id'];
                    $userDetails['synset_terms'] = $translateText['synset_terms'];
                    $userDetails['gloss'] = $translateText['gloss'];
                    $userInfo = $userDetails;
                }
                break;
            case 'user':
                $userInfo = $this->user->where(['user_id'=>$userId])->first();
                break;
            default:
                abort(404);    
        }
        
        return response()->json($userInfo);
    }

    function saveUserSettings(Request $request){
        if ($request->session()->has('logged')){
            $this->user->where(['user_id'=>$request->session()->get('userSession')['user_id']])->update(['dialect'=>$request->dialect]);
            return response()->json($request);  
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

     function saveScore(Request $request){
        if ($request->session()->has('logged')){
            $leaderboard = $this->leaderboard->where('user_id',$request->session()->get('userSession')['user_id'])->first();
            // return response()->json(count($leaderboard));
            if($request->guessed == 1){ //Insert guess_word
                if(count($leaderboard) < 1){ //Insert
                    $this->leaderboard->user_id = $request->session()->get('userSession')['user_id'];
                    $this->leaderboard->guessed_word = 1;
                    $this->leaderboard->save();
                } else { // Update
                    $this->leaderboard->where(['user_id'=>$request->session()->get('userSession')['user_id']])->update(['guessed_word'=>$leaderboard->guessed_word + 1]);
                }
                return response()->json(1);
            }
            // $this->user->where(['user_id'=>$request->session()->get('userSession')['user_id']])->update(['dialect'=>$request->dialect]);
            return response()->json($request);  
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    private function getTranslateText($limit){
        $basetb = \App\BaseTB::whereRaw("char_length(btrim(synset_terms[1],'#0123456789')) <= ".$limit." AND char_length(btrim(synset_terms[1],'#0123456789')) > 1 ")->orderByRaw('RANDOM()')->first();
        // dump($basetb);
        $synsetOne = str_replace('#','',str_replace('_',' ',explode(",", substr($basetb->synset_terms, 1, -1))));
        $transArray = [];
        $transArray['base_id'] = $basetb->base_id;
        $transArray['synset_terms'] =  substr( strtoupper($synsetOne[0]), 0, -2);
        $transArray['gloss'] = $basetb->gloss;
        return $transArray;
    }

    public function getTopPlayers(){
        $top = DB::select('select a.user_id, u.username, max(a.arcade_id) lvl, b.translated_word, b.guessed_word from dictionary.user_arcade_fact a left join dictionary.user_leaderboard_tb b on a.user_id = b.user_id join dictionary.users_tb u on a.user_id = u.user_id group by a.user_id, u.username ,b.translated_word, b.guessed_word order by lvl desc limit 5');
        return response()->json($top);
    }

    public function getStats(Request $request){
        $stats = DB::select('select max(ua.arcade_id) lvl, ul.translated_word translated , ul.guessed_word guessed from dictionary.users_tb utb
                            left join dictionary.user_arcade_fact ua
                            on utb.user_id = ua.user_id
                            join dictionary.user_leaderboard_tb ul
                            on ul.user_id = utb.user_id
                            where utb.user_id = '.$request->session()->get('userSession')['user_id'].'
                            group by utb.user_id, ul.translated_word,ul.guessed_word');
        $stats = ($stats == null) ? 0 : $stats[0];
        return response()->json($stats);
    }


    function checkUserLogged(Request $request){
        if ($request->session()->has('userSession')){ 
            return response()->json(true);
        } else{
            return response()->json(false);
        }
    }

    function deleteSession(Request $request){
        $request->session()->flush();
    }



}
