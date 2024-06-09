<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\ThemeRejected;
use App\Mail\ThemeAccepted;
use App\Models\Comment;
use App\Models\Themes;
use App\Models\NewsFeed;
use App\Models\Poll;
use App\Models\User;
use App\Models\PollResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class ThemeController extends Controller
{

    //Home Page
    public function home(){
        return view('home', [
            'themes' => Themes::where('approved', 'true')->latest()->filter(request(['search']))
                ->paginate(4),
            'newsFeed' => NewsFeed::latest()->get()
        ]);
    }


    //Contact Page
    public function contact(){
        return view('contact');
    }


    //Count Themes
    public function countThemes($moderatorId)
    {
        return Themes::where('user_id', $moderatorId)->count();
    }



    // Get All Themes
    public function index(){
        return view('theme.index', [
            'themes' => Themes::where('user_id', auth()->id())->latest()->filter(request(['search']))
                ->paginate(3),
            'newsFeed' => NewsFeed::latest()->get()
        ]);
    }


    //Show Form For Theme Creation
    public function create(){
        return view('theme.create');
    }



    public function show(Themes $theme)
    {
        $comments = Comment::where('theme_id', $theme->id)->paginate(3);
        $polls = Poll::where('theme_id', $theme->id)->get();

        return view('theme.show', [
            'theme' => $theme,
            'comments' => $comments,
            'polls' => $polls,
        ]);
    }





    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required', Rule::unique('themes', 'title')],
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('storage'), $imageName);

        $formFields['user_id'] = auth()->id();
        $formFields['image'] = $imageName;


        $formFields['approved'] = false;

        Themes::create($formFields);

        $themeTitle = $formFields['title'];
        NewsFeed::create([
            'content' => auth()->user()->name . " je kreirao/la novu temu. Tema je:  '$themeTitle'",
        ]);

        return redirect('/')->with('message','Tema je poslata na odobrenje administratoru.');
    }




    //Show Edit Form
    public function edit(Themes $theme){
        return view('theme.edit', ['theme' => $theme]);
    }


    //Update Theme Data
    public function update(Request $request, Themes $theme)
    {

        if ($theme->user_id != auth()->id()) {
            abort(403, 'Nedozvoljena radnja');
        }


        $formFields = $request->validate([
            'title' => ['required'],
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage'), $imageName);
            $formFields['image'] = $imageName;
        }

        $theme->update($formFields);

        $themeTitle = $formFields['title'];
        NewsFeed::create([
            'content' => auth()->user()->name . " je izmenio/la temu '$themeTitle'",
        ]);

        return back()->with('message', 'Tema uspešno ažurirana!');
    }



    //Delete Theme
    public function destroy(Themes $theme)
    {

        if($theme->user_id != auth()->id()){
            abort(403,'Unauthorized Action');
        }

        $theme->comments()->delete();

        $themeTitle = $theme->title;
        NewsFeed::create([
            'content' => auth()->user()->name . " je obrisao/la temu '$themeTitle'",
        ]);

        $theme->delete();

        return redirect('/')->with('message', "Tema uspešno obrisana!");
    }



    // Reject theme
    public function rejectTheme(Themes $theme)
    {
        DB::table('themes')->where('id', $theme->id)->update(['approved' => 0]);

        //Mail::to($theme->user->email)->send(new ThemeRejected($theme));

        return redirect()->back()->with('message', 'Tema je odbijena.');
    }



    // Accetp theme
    public function acceptTheme(Themes $theme)
    {
        DB::table('themes')->where('id', $theme->id)->update(['approved' => 1]);

        // Mail::to($theme->user->email)->send(new ThemeAccepted($theme));

        return redirect()->back()->with('message', 'Tema je prihvaćena.');
    }



    public function followedThemes()
    {
        $followedThemes = auth()->user()->followedThemes()->paginate(10);

        return view('followed-themes.index', compact('followedThemes'));
    }



    public function followTheme(Themes $theme)
    {
        $user = auth()->user();
        $user->followedThemes()->attach($theme->id);

        return redirect()->back()->with('message', 'Uspešno ste zapratili temu.');
    }

    public function unfollowTheme(Themes $theme)
    {
        $user = auth()->user();
        $user->followedThemes()->detach($theme->id);

        return redirect()->back()->with('message', 'Uspešno ste otpratili temu.');
    }




    //Manage Themes
    public function manage(){

        return view('theme.manage',['themes' => auth()->user()->themes()->get()]);
    }


    //Anketa
    public function createPollForm(Themes $theme)
    {
        return view('theme.create-poll', compact('theme'));
    }


    public function storePoll(Request $request, $themeId)
    {

        $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
        ]);


        $theme = Themes::findOrFail($themeId);


        $poll = new Poll();
        $poll->theme_id = $themeId;
        $poll->question = $request->question;
        $poll->options = json_encode($request->options);
        $poll->save();


        $newsFeedContent = "Nova anketa je kreirana na temu '{$theme->title}' od strane kreatora teme " . auth()->user()->name;
        NewsFeed::create([
            'content' => $newsFeedContent,
        ]);

        return redirect()->back()->with('message', 'Anketa uspješno stvorena.');
    }


    //Show form for poll

    public function showDetails($pollId)
    {
        $poll = Poll::findOrFail($pollId);
        $theme = Themes::findOrFail($poll->theme_id);

        $totalResponses = PollResponse::where('poll_id', $pollId)->count();

        $responsePercentages = [];
        foreach (json_decode($poll->options) as $option) {
            $responseCount = PollResponse::where('poll_id', $pollId)
                                        ->where('response', $option)
                                        ->count();
            $responsePercentages[$option] = $totalResponses > 0 ? ($responseCount / $totalResponses) * 100 : 0;
        }

        $isModerator = Auth::check() && $theme->user_id == Auth::id();

        return view('theme.details', compact('poll', 'theme', 'responsePercentages', 'isModerator'));
    }




    //Save response from poll
    public function storePollResponse(Request $request, Themes $theme)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('message', 'Morate biti prijavljeni da biste mogli popuniti anketu.');
        }

        $existingResponse = PollResponse::where('poll_id', $request->poll_id)
                                        ->where('user_id', Auth::id())
                                        ->first();

        if ($existingResponse) {
            return redirect()->back()->with('message', 'Već ste dali odgovor na ovu anketu.');
        }

        $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'theme_id' => 'required|exists:themes,id',
            'response' => 'required',
        ]);

        $response = new PollResponse();
        $response->poll_id = $request->poll_id;
        $response->theme_id = $request->theme_id;
        $response->user_id = Auth::id();
        $response->response = $request->response;
        $response->save();


        return redirect()->back()->with('message', 'Odgovor na anketu je uspešno spremljen.');
    }


    //Delete poll
    public function deletePoll(Poll $poll)
    {
        $poll->delete();

        NewsFeed::create([
            'content' => 'Anketa "' . $poll->question . '" je zatvorena.',
        ]);

        return redirect('/themes')->with('message', 'Anketa je uspešno zatvorena i obrisana.');
    }


    public function followedThemesIndex($themeId)
    {

        $theme = Themes::findOrFail($themeId);

        $followers = $theme->followers()->get();

        return view('followed-themes.followers', compact('followers', 'theme'));
    }


    public function deleteFollower($themeId, $followerId)
    {

        $theme = Themes::findOrFail($themeId);

        $follower = $theme->followers()->where('users.id', $followerId)->first();

        if ($follower) {
            $theme->followers()->detach($followerId);

            return redirect()->back()->with('message', 'Korisnik je uspešno obrisan kao pratilac teme.');
        } else {
            return redirect()->back()->with('message', 'Korisnik nije pronađen kao pratilac teme.');
        }
    }
}


