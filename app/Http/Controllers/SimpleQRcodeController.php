<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpleQRcodeController extends Controller
{
    // L'action "generate" de la route "simple-qrcode" (GET)
    public function generate () {

        # 2. On génère un QR code de taille 200 x 200 px
        $qrcode = QrCode::size(200)->generate("Je suis un QR Code");

        # 3. On envoie le QR code généré à la vue "simple-qrcode"
        return view("simple-qrcode", compact('qrcode'));
    }
}
