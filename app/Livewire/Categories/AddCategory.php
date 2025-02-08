<?php

namespace App\Livewire\categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AddCategory extends Component
{
    use WithFileUploads;

    public $posts, $title, $body, $post_id;
    public $updateMode = false;
   

    public $nom, $photo,$photo2,$category,$description;


    public function mount($category){
        if($category){
            $this->category = $category;
            $this->nom = $category->nom;
            $this->description = $category->description;
           
            $this->photo2 = $category->photo;
           
        }
    }

    private function resetInputFields(){
        $this->nom = '';
        $this->description = '';
    }




    public function render()
    {
        return view('livewire.categories.add-category');
    }




    

    //validation
    public function create()
    {
        $this->validate([
            'nom' => 'required|string',
            'description' => 'nullable|string|Max:5000000',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);
        ;[
            'description.required' => 'La description doit avoir moins de 5000 caractères',
            'nom.required' => 'Veuillez entrer le nom ',
           'photo.required' => 'Veuillez  mettre une photo',
            //'adresse.required' => 'Veuillez entrer votre addresse',
      
          ];

        $category = new Category();
        $category->nom = $this->nom;
        $category->description = $this->description;
        $category->photo = $this->photo->store('categories', 'public');
        
    
        
  
        $category->save();

        
        $this->resetInput();
     
        session()->flash('success', 'category ajoutée avec succès');
    }




    public function update_category(){
        if($this->category){
            $this->validate([
                'nom' => 'required|string',
             //   'description' => 'required|string',
             'description' => 'nullable|string|Max:5000000',
              
              
                'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
               
            ]);



            $this->category->nom = $this->nom;
            $this->category->description = $this->description;
            
            if ($this->photo) {
                // Vérifier si une ancienne image existe
                if ($this->category->photo && file_exists(public_path('Image/' . $this->category->photo))) {
                    unlink(public_path('Image/' . $this->category->photo)); // Supprime l'ancienne image
                }
        
                // Enregistrer la nouvelle image
                $filename = time(). '.'. $this->photo->getClientOriginalExtension();
                
                // Stockage temporaire
                $path = $this->photo->storeAs('Image', $filename, 'public');

                // Déplacement manuel vers public/Image/    
                $sourcePath = storage_path("app/public/$path");
                $destinationPath = public_path("Image/$filename");
                copy($sourcePath, $destinationPath);
                $this->category->photo = $filename;

                
                   
            
            }

        
            $this->category->save();
    
  
            $this->resetInput();
    
            return redirect()->route('categories')->with('success',"category modifié avec succès");



        }
    }




    public function resetInput()
    {
        $this->nom = '';
        $this->description = '';
        $this->photo = '';
    }
}
