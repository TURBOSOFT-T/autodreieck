<div>
    <form  wire:submit="confirmOrder" >
        @if ($errors->any())
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        @endif
        @csrf
        <div class="row">
            <div class="col-lg-6">

                <div class="axil-checkout-billing">
                    <h4 class="title mb--40">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Détails factures') }}</h4>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label> {{ \App\Helpers\TranslationHelper::TranslateText('Nom') }}
                                    <span>*</span></label>
                                <input style=" background-color: #fbecec" type="text" name="nom" wire:model.lazy="nom" wire:model.lazy="nom" 

                                    @if (Auth::user()) value="{{ Auth::user()->nom }}" @endif
                                    required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{ \App\Helpers\TranslationHelper::TranslateText('Prénom') }}<span>*</span></label>
                                <input style=" background-color: #fbecec" type="text" name="prenom" wire:model.lazy="prenom" wire:model.lazy="prenom"  
                                    @if (Auth::user()) value="{{ Auth::user()->prenom }}" @endif
                                    required />

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Email <span>*</span></label>
                                <input style=" background-color: #fbecec" type="mail" name="email" wire:model.lazy="email" wire:model.lazy="email" 
                                    @if (Auth::user()) value="{{ Auth::user()->email }}" @endif
                                    required />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{ \App\Helpers\TranslationHelper::TranslateText('Téléphone') }}<span>*</span></label>
                                <input style=" background-color: #fbecec" type="number" name="phone" wire:model.lazy="phone" wire:model.lazy="phone" 
                                    required />

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label> {{ \App\Helpers\TranslationHelper::TranslateText('Adresse') }}
                            <span>*</span></label>

                        <input style=" background-color: #fbecec" type="text" name="adresse" wire:model.lazy="adresse" wire:model.lazy="adresse" 
                            class="mb--15"
                            placeholder=" {{ \App\Helpers\TranslationHelper::TranslateText('Votre adresse') }}"
                            required />
                    </div>

                    <div class="form-group mb-3">
                        <p wire:loading ></p>
                        <select  name="country"  id="country_id" wire:model="country_id" wire:model.lazy="country_id" class="form-control"
                            style=" background-color: #fbecec">
                            <option value=""> {{ \App\Helpers\TranslationHelper::TranslateText('--Choisir pays--') }}</option>
                            @foreach ($countries as $country)
                                <option value="{{  $country->id }}" >
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($states->count())
                    <div class="form-group mb-3">
                        <p wire:loading ></p>
                        <select class="form-control" name="state_id" id="city_id" wire:model.lazy="state_id"  wire:model="state_id">
                            <option value="" selected   style=" background-color: #fbecec">{{ \App\Helpers\TranslationHelper::TranslateText('--Choisir région--') }}</option>
                            @foreach($states as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @if($cities->count())
                    <div class="form-group">
                      
                        <select class="form-control" name="city_id" id="city_id" wire:model="city_id"  wire:model.lazy="city_id" >
                            <option value="" selected   style=" background-color: #fbecec">{{ \App\Helpers\TranslationHelper::TranslateText('--Choisir ville--') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif 


<br><br>

                    <div class="form-group">
                        <label>
                            {{ \App\Helpers\TranslationHelper::TranslateText('Messge(optionnel)') }}
                        </label>
                        <textarea style=" background-color: #fbecec" id="message" rows="2"  wire:model.lazy="note" wire:click="note" 
                            placeholder=" {{ \App\Helpers\TranslationHelper::TranslateText('Note sur votre commande(Optionnel)') }}"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="axil-order-summery order-checkout-summery">
                    <h5 class="title mb--20">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Votre commande') }}</h5>
                    <div class="summery-table-wrap">
                        <table class="table summery-table">
                            <thead>
                                <tr>
                                    <th> {{ \App\Helpers\TranslationHelper::TranslateText('Produit') }}
                                    </th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paniers as $id => $details)
                                    <tr class="order-product">
                                        <td>{{ $details['nom'] }} <span class="quantity">x
                                                {{ $details['quantite'] }}</span></td>
                                        <td> {{ $details['total'] }} <x-devise></x-devise></td>

                                    </tr>
                                @endforeach

                                <tr class="order-subtotal">
                                    <td>Subtotal</td>
                                    <td>{{ $total }} <x-devise></x-devise></td>
                                </tr>


                                <tr class="order-shipping">

                            <tbody>
                                <td colspan="2">
                                    <tr>
                                        <td class="tax">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('Frais de livraison') }}
                                        </td>
                                        <td>{{ $configs->frais ?? 0 }}
                                            <x-devise></x-devise>
                                        </td>
                                    </tr>
                                  {{--   <tr>
                                        <td class="tax">
                                            {{ \App\Helpers\TranslationHelper::TranslateText('Coupon de réduction') }}
                                        </td>
                                        <td>-{{ session('coupon')['value'] ?? 0 }}
                                            <x-devise></x-devise>
                                        </td>
                                    </tr> --}}
                                </td>



                            </tbody>
                        
                            </tr>
                            <tr class="order-total">
                                <td>Total</td>
                                <td class="order-total-amount">{{ $total + $configs->frais ?? 0 }}
                                    <x-devise></x-devise></td>
                            </tr>
                            </tbody>
                        </table>

                        
                       

                       
                    </div>

                   

                    <button type="submit" class="axil-btn btn-bg-primary2 checkout-btn">
                        {{ \App\Helpers\TranslationHelper::TranslateText('Confirmation') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
