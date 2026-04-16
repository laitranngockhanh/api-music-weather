<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id; 
        $albums = Album::where('user_id', $userId)->get();
        return response()->json($albums);
    }

    public function show($id)
{
    try {
        $userId = auth()->user()->id;
        $album = Album::where('user_id', $userId)->findOrFail($id);

        $songs = $album->songs()
            ->leftJoin('user_song', function ($join) use ($userId) {
                $join->on('songs.id', '=', 'user_song.song_id')
                     ->where('user_song.user_id', '=', $userId);
            })
            ->select(
                'songs.*',
                'user_song.custom_name as override_name',
                'user_song.custom_artist as override_artist'
            )
            ->get()
            ->map(function ($song) {
                return [
                    'song_id' => $song->id,
                    'custom_name' => $song->override_name ?? ($song->name ?? 'Unknown'),
                    'custom_artist' => $song->override_artist ?? ($song->artist ?? 'Unknown'),
                    'file_path' => $song->file_path,
                ];
            });

        return response()->json([
            'id' => $album->id,
            'album_name' => $album->album_name,
            'songs' => $songs,
            'song_count' => $songs->count(),
        ], 200);
    } catch (\Exception $e) {
        Log::error('Lỗi khi lấy thông tin album: ' . $e->getMessage(), [
            'album_id' => $id,
            'user_id' => $userId,
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['message' => 'Lỗi khi lấy thông tin album: ' . $e->getMessage()], 500);
    }
}

    public function store(Request $request)
    {
        $userId = auth()->user()->id;
        $request->validate(['album_name' => 'required|string|max:255']);
        $album = Album::create([
            'album_name' => $request->album_name,
            'user_id' => $userId,
        ]);
        return response()->json($album, 201);
    }

    public function update(Request $request, $id)
    {
        $userId = auth()->user()->id;
        $album = Album::where('user_id', $userId)->findOrFail($id);
        $request->validate(['album_name' => 'required|string|max:255']);
        $album->update(['album_name' => $request->album_name]);
        return response()->json($album);
    }

    public function delete($id)
    {
        $userId = auth()->user()->id;
        $album = Album::where('user_id', $userId)->findOrFail($id);
        $album->delete();
        return response()->json(['message' => 'Album đã bị xóa']);
    }
}
