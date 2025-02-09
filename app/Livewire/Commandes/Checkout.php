<?php

namespace App\Livewire\Commandes;

use Livewire\Component;
use App\Http\Requests\commandes\CommandesRequest;
use Illuminate\Http\Request;
use App\Models\{commandes, produits, Coupon, contenu_commande, config, Country, notifications, User, Transport};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
//use Illuminate\Support\Facade\Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OrderMail;
use App\Mail\FirstOrder;
use App\Models\City;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewOrder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Mail\Mailable;
use App\Services\PayUService\Exception;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Coupons;

class Checkout extends Component
{

    public $key, $nom, $prenom, $frais, $adresse,  $phone, $email, $note, $mode, $transport;

    public $cart;

    public $states;

    public $country_id;
    public $state_id;
    public $city_id;
    public $cities;



    public function mount()
    {


        $this->nom = Auth::check() ? Auth::user()->nom : '';
        $this->prenom = Auth::check() ? Auth::user()->prenom : '';
        $this->adresse = Auth::check() ? Auth::user()->adresse : '';

        $this->phone = Auth::check() ? Auth::user()->phone : '';
        $this->email = Auth::check() ? Auth::user()->email : '';
        $this->cart = session('cart', []);
        if (Auth::check()) {
            $user = Auth::user();
            $this->nom = $user->nom;
            $this->prenom = $user->prenom;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }

        $this->states = collect();
        $this->cities = collect();
    }



    public function updatedCountryId($newValue)
    {
        $this->states = State::where("country_id", $newValue)->orderBy("name")->get();
    }

    public function updatedStateId($newValue)
    {
        $this->cities = City::where("state_id", $newValue)->orderBy("name")->get();
    }


    protected $rules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'nullable|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string',
        'adresse' => 'required|string',
        'note' => 'nullable|string',
        'country_id' => 'nullable|exists:countries,id',
      

    ];


    public function render()
    {
        $configs = config::firstOrFail();
        // $paniers_session = session('cart');

        $paniers_session = session('cart', []);

        // Vérifier que $paniers_session est bien un tableau
        if (!is_array($paniers_session)) {
            $paniers_session = [];
        }
        $paniers = [];
        $total = 0;
        if (empty($paniers_session)) {
            request()->session()->flash('error', 'La panier est vide !');
            return back();
        }




        foreach ($paniers_session as $session) {
            $produit = produits::find($session['id_produit']);
            if ($produit) {
                $paniers[] = [
                    'nom' => $produit->nom,
                    'id_produit' => $produit->id,
                    'photo' => $produit->photo,
                    'quantite' => $session['quantite'],
                    'prix' => $produit->getPrice(),
                    'total' => $session['quantite'] * $produit->getPrice(),
                ];
                if (session()->has('coupon')) {
                    $total += $session['quantite'] * $produit->getPrice() - session('coupon')['value'];
                } else {
                    $total += $session['quantite'] * $produit->getPrice();
                }

                //  dd($total);
            }
        }
        $countries = Country::select("id", "name")->get();


        return view('livewire.commandes.checkout',   compact('configs', 'paniers', 'total', 'countries'));
    }



    public function confirmOrder(Request $request)
    {

        $connecte = Auth::user();
        $configs = config::firstOrFail();
        $total = 0;
       

        if ($connecte) {
            $order = new commandes();
            $this->validate();


            $order->user_id = $connecte->id;
            $order->nom = $this->nom;
            $order->prenom = $this->prenom;
            $order->adresse = $this->adresse;

            $order->phone = $this->phone;
            $order->email = $this->email;
            $order->note = $this->note;
            $order->country_id = $this->country_id;
            $order->state_id = $this->state_id;
            $order->city_id = $this->city_id;
        } else {
            $this->validate();
            $order = new commandes();
            $order->nom = $this->nom;
          //  $order->user_id = $user->id;
       $order->prenom = $this->prenom;
       $order->email = $this->email;
      $order->adresse = $this->adresse;
       $order->phone = $this->phone;
       $order->note = $this->note;
       $order->country_id = $this->country_id;
       $order->state_id = $this->state_id;
      $order->city_id = $this->city_id;


        }
//dd($order);

        $order->save();
        $existingUsersWithEmail = User::where('email', $this->email)->exists();

        if (!$existingUsersWithEmail) {
            $this->validate();
            $user = new User();
            $user->nom = $this->nom;
            $user->prenom = $this->prenom;
            $user->email = $this->email;
            $user->password = Hash::make($this->phone);
            $user->phone = $this->phone;
           $user->save();
        //    Mail::to($user->email)->send(new FirstOrder($user));


        }

        $paniers_session = Session::get('cart') ?? [];
        $total = 0;

        foreach ($paniers_session as $session) {
            $produit = produits::find($session['id_produit']);
            if ($produit) {

                $items =   contenu_commande::create([
                    'id_commande' => $order->id,
                    'id_produit' => $produit->id,
                    'prix_unitaire' => $produit->getPrice(),
                    'quantite' => $session['quantite'],

                ]);


                $produit->diminuer_stock($session['quantite']);
            }
        }

        //envoyer les emails
         $this->sendOrderConfirmationMail($order);

        //effacer le panier
       //  session()->forget('cart');
        session()->forget('coupon');

        //generate notification
        $notification = new notifications();
        $notification->url = route('details_commande', ['id' => $order->id]);
        $notification->titre = "Nouvelle commande.";
        $notification->message = "Commande passée par " . $order->nom;
        $notification->type = "commande";
        $notification->save();


        return redirect()->route('thank-you');
    }





    public function sendOrderConfirmationMail($order)
    {

      //  Mail::to($order->email)->send(new OrderMail($order));
      try {
        Mail::to($order->email)->send(new OrderMail($order));
        session()->flash('success', 'Email envoyé avec succès !');
    } catch (\Exception $e) {
        \Log::error('Erreur d\'envoi d\'email : ' . $e->getMessage());
        session()->flash('error', 'Échec de l\'envoi de l\'email. Veuillez réessayer.');
    }
    }
}
