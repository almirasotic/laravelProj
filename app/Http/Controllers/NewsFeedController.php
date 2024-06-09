<?php

namespace App\Http\Controllers;

use App\Models\NewsFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsFeedController extends Controller
{

    //Create NewsFeed
    public function create(Request $request){


        $formFields = $request->validate([
            'content' => 'required',
        ]);

        NewsFeed::create($formFields);


        return back()->with('message', 'Vest uspešno dodata!');
    }


    //Delete NewsFeed
    public function destroy(NewsFeed $feed){

        if(!$feed){
            return back()->with('message', 'Vest nije pronadjena!');
        }

        $feed->delete();

        return back()->with('message', 'Vest uspešno obrisana.');
    }


    //Send message to apply for the moderator
    public function applyForModerator()
    {
        $user = auth()->user();

        if ($user->role === 'korisnik') {
            DB::table('users')->where('id', $user->id)->update(['request' => 'applied']);

            return back()->with('message', 'Vaša prijava je poslata.');
        }

        return back()->with('message', 'Niste kvalifikovani da se prijavite za ulogu moderatora.');
    }

}
