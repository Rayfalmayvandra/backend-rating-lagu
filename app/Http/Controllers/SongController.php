<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index() {
        return response()->json(Song::all(), 200);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
            'cover' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        $song = Song::create($data);
        return response()->json($song, 201);
    }

    public function show($id) {
        $song = Song::find($id);
        if (!$song) return response()->json(['message' => 'Lagu tidak ditemukan'], 404);
        return response()->json($song, 200);
    }

    public function update(Request $request, $id) {
        $song = Song::find($id);
        if (!$song) return response()->json(['message' => 'Lagu tidak ditemukan'], 404);

        $song->update($request->all());
        return response()->json($song, 200);
    }

    public function destroy($id) {
        $song = Song::find($id);
        if (!$song) return response()->json(['message' => 'Lagu tidak ditemukan'], 404);

        $song->delete();
        return response()->json(['message' => 'Lagu berhasil dihapus'], 200);
    }
}