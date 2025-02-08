@extends('front.fixe')
@section('titre', 'Paiement')
@section('body')
    <main>

        <body class="sticky-header">
           

         
            <a href="#top" class="back-to-top" id="backto-top"><i class="fal fa-arrow-up"></i></a>

            <main class="main-wrapper">
                <div class="axil-breadcrumb-area">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-8">
                                <div class="inner">
                                    <ul class="axil-breadcrumb">
                                        <li class="axil-breadcrumb-item"><a href="{{ route('home') }}">
                                                {{ \App\Helpers\TranslationHelper::TranslateText('Accueil') }}</a></li>
                                        <li class="separator"></li>
                                        <li class="axil-breadcrumb-item1 active" aria-current="page"> {{ __('boutique') }}
                                        </li>
                                    </ul>

                                    <h1 class="title">
                                        {{ \App\Helpers\TranslationHelper::TranslateText('Confirmation de votre commande') }}
                                    </h1>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4">
                                {{-- <div class="inner">
                                    <div class="bradcrumb-thumb">
                                        <img src="{{ Storage::url($config->image_shop) }}" alt="Image">
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

          
                <div class="axil-checkout-area axil-section-gap">
                    <div class="container">

                        @livewire('commandes.checkout')
                 
                    </div>
                </div>
                <!-- End Checkout Area  -->
                <style>
                    .btn-bg-primary2 {
                        background-color: #e40f0f;
                        color: #ffffff;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        text-decoration: none;
                    }

                    .btn-bg-secondary2 {
                        background-color: #EFB121;
                        /* Couleur de fond, bleu dans cet exemple */
                        color: #ffffff;
                        /* Couleur du texte, blanc dans cet exemple */
                        border: none;
                        padding: 10px 20px;
                        /* Optionnel, ajuste la taille */
                        border-radius: 5px;
                        /* Optionnel, arrondit les coins */
                        text-decoration: none;
                        /* Supprime le soulignement */
                    }
                </style>


            </main>

    </main>

@endsection
