<?php

namespace App\Http\Controllers;

use App\Models\PieceJointe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PieceJointeController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', 
            'objet_type' => 'required|string',
            'objet_id' => 'required|integer',
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads/' . date('Y/m'), 'public');

        $pieceJointe = PieceJointe::create([
            'objet_type' => $request->objet_type,
            'objet_id' => $request->objet_id,
            'nom_fichier' => $file->getClientOriginalName(),
            'chemin' => $path,
            'mime' => $file->getMimeType(),
            'taille' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return $this->successResponse($pieceJointe, 'Fichier uploadé avec succès', 201);
    }

    public function download(PieceJointe $pieceJointe)
    {
        if (!Storage::disk('public')->exists($pieceJointe->chemin)) {
            return $this->errorResponse('Fichier non trouvé', 404);
        }

        return Storage::disk('public')->download($pieceJointe->chemin, $pieceJointe->nom_fichier);
    }

    public function destroy(PieceJointe $pieceJointe)
    {
        Storage::disk('public')->delete($pieceJointe->chemin);
        $pieceJointe->delete();

        return $this->successResponse(null, 'Fichier supprimé');
    }
}