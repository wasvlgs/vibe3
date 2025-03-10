<?php

namespace App\Http\Controllers;

use App\Models\Ami;
use App\Models\Conversation;
use App\Models\User;
use App\Notifications\FriendRequestAccepted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;


class AmiController extends Controller
{
    /**
     * Afficher la liste des demandes d'amis.
     */
    public function index()
    {
        $amis = Ami::with(['sender', 'receiver'])->get();

    }

    /**
     * Envoyer une demande d'ami.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sender' => 'required|exists:users,id',
            'id_receiver' => 'required|exists:users,id',
        ]);

        $ami = Ami::create([
            'id_sender' => $request->id_sender,
            'id_receiver' => $request->id_receiver,
            'status' => 'pending',
        ]);
        return back();    }

    /**
     * Afficher une demande d'ami spécifique.
     */
    public function show($id)
    {
        $ami = Ami::with(['sender', 'receiver'])->findOrFail($id);
    }

    /**
     * Mettre à jour une demande d'ami.
     */
    public function update(Request $request, $id)
    {
        $ami = Ami::findOrFail($id);
        $request->validate([
            'status' => 'nullable|string|in:pending,accepted,declined',
        ]);

        $ami->update($request->only(['status']));

        return response()->json($ami);
    }

    /**
     * Supprimer une demande d'ami.
     */
    public function destroy($id)
    {
        $ami = Ami::findOrFail($id);
        $ami->delete();

        return response()->json(['message' => 'Demande d\'ami supprimée avec succès.']);
    }

    /**
     * Mettre à jour uniquement le statut d'une demande d'ami.
     */
    public function updateStatus(Request $request, $id)
    {
        $ami = Ami::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:pending,accepted,declined',
        ]);

        $ami->status = $request->status;
        $ami->save();

        return response()->json(['message' => 'Statut mis à jour avec succès.', 'ami' => $ami]);
    }


    public function accepter($id)
    {
        $request = Ami::findOrFail($id);

        // Ensure the authenticated user is accepting the request
        if (auth()->id() == $request->id_receiver) {
            // Update the request status to 'accepted'
            $request->update(['status' => 'accepted']);

            // Get the sender (the user who sent the friend request)
            $sender = User::find($request->id_sender);

            // Notify the receiver (the authenticated user) via broadcast
            $request->receiver->notify(new FriendRequestAccepted($sender));
        }
        Conversation::create([
            'user_one_id' => $sender->id,
            'user_two_id' => $request->id_receiver, // authenticated user
        ]);

        return back()->with('success', 'Friend request accepted!');
    }


    public function annuler($id)
    {
        $request = Ami::findOrFail($id);
        if (auth()->id() == $request->id_receiver) {
            $request->delete();
        }
        return back()->with('success', 'Friend request canceled!');
    }
    public function generateInviteLink()
    {
        try {
            $link = URL::temporarySignedRoute('accept.invitation', now()->addMinutes(60), [
                'id_sender' => Auth::id(),
            ]);

            return response()->json(['link' => $link], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la génération du lien.'], 500);
        }
    }

    public function generateQRCode()
    {
        try {
            $link = URL::temporarySignedRoute('accept.invitation', now()->addMinutes(60), [
                'id_sender' => Auth::id(),
            ]);

            $qrCode = QrCode::format('png')->size(200)->generate($link);
            return new StreamedResponse(function () use ($qrCode) {
                echo $qrCode;
            }, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'attachment; filename="qr_code.png"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la génération du code QR.'], 500);
        }
    }

    /**
     * Accepter une invitation via le lien ou le QR code.
     */
    public function acceptInvitation(Request $request)
    {
        $senderId = $request->id_sender;
        $receiver = auth()->user();

        if (!$senderId || $receiver->id == $senderId) {
            return redirect()->route('profile.edit')->with('error', 'Lien invalide ou auto-invitation non autorisée.');
        }

        // Check if the sender is already a friend
        $alreadyFriend = $receiver->getFriends()
            ->where('id', $senderId)->isNotEmpty();

        if ($alreadyFriend) {
            return redirect()->route('dashboard')->with('error', 'Cet utilisateur est déjà dans votre liste d\'amis.');
        }

        // Create or update the friend request
        Ami::updateOrCreate(
            [
                'id_sender' => $senderId,
                'id_receiver' => $receiver->id,
            ],
            [
                'status' => 'accepted',
            ]
        );
        Conversation::create([
            'user_one_id' => $senderId,
            'user_two_id' => $receiver->id, // authenticated user
        ]);

        return redirect()->route('dashboard')->with('success', 'Vous êtes maintenant amis !');
    }

}
