<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function inscription(Request $request){
        $utilisateurDonnee = $request -> validate([
                "name" => ["required", "string", "min:2", "max:255"],
                "email" => ["required", "email", "unique:users,email"],
                "password" => ["required","string", "min:8", "max:30", "confirmed"]

            ]);
            $utilisateurs = User::create([
                "name" => $utilisateurDonnee["name"],
                "email" => $utilisateurDonnee["email"],
                "password" => bcrypt($utilisateurDonnee["password"])
            ]);
            return response($utilisateurs,201);

    }
    public function connexion(Request $request) {
        $utilisateurDonnee = $request -> validate([
            "email" => ["required", "email"],
            "password" => ["required","string", "min:8", "max:30"]

        ]);
        $utilisateur = User::where("email",$utilisateurDonnee["email"])->first();
        if(!$utilisateur) return response(["message" => "Aucun utilisateur de trouver avec l'email suivante $utilisateurDonnee[email]"],401); 
        /* if(!Hash::check($utilisateurDonnee["password"], $utilisateur->xpassword))
        {
        return response(["message" => "Aucun utilisateur de trouver avec ce mot de passe "],401);
          }  */
         $token = $utilisateur->createToken("CLE_SECRETE")->plainTextToken;
        return response([
            "utilisateur" => $utilisateur,
            "token" => $token
        ], 200);
        
    }
     
}   
