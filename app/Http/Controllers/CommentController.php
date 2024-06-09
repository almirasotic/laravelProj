<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    //Show Form For Comment Creation
    public function create(Request $request){

        $themeTitle = $request->query('themeTitle');
        $themeId = $request->query('themeId');

        return view('theme.addComment', compact('themeTitle', 'themeId'));
    }



    // Save comment
    public function store(Request $request, $themeId)
{
    try {
        $request->validate([
            'comment' => 'required',
        ]);

        // Ručno konvertujte themeId u integer
        $themeId = (int) $themeId;

        $data = [
            'content' => $request->input('comment'),
            'user_id' => auth()->id(),
            'theme_id' => $themeId,
        ];
        Comment::create($data);

        return redirect('/')->with('message', 'Komentar uspešno dodat!');
    } catch (\Exception $e) {
        logger()->error('Greška prilikom čuvanja komentara: ' . $e->getMessage());
        return redirect('/')->with('message', 'Greška prilikom čuvanja komentara. Molimo pokušajte ponovo.');
    }
}


    //Delete comment
    public function destroy($id)
    {

        $comment = Comment::findOrFail($id);

        $comment->delete();

        return redirect()->back()->with('message', 'Komentar je uspešno obrisan.');
    }

}
