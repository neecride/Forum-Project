<?php 

namespace App;

class ValidationError {

    private $key;
    private $rule;
    private $attributes;
    private $messages = [
        'required'          => "Le champ %s est requis",
        'empty'             => "Le champ %s ne peut être vide",
        'slug'              => "Le champ %s n'est pas un slug valide",
        'minLength'         => "Le champ %s doit contenir plus de %d caractères",
        'maxLegnth'         => "Le champ %s doit contenir mins de %d caractères",
        'betweenLength'     => "Le champ %s doit contenir entre %d et %d caractères",
        'dateTime'          => "Le champ %s doit être une date valide (%s)",
        'fileExist'         => "Le fichier %s n'existe pas",
        'validName'         => "Le champ %s doit contenir 3|20 caractères alphanuméric (accent compris) tirets (-) et underscores (_) pas d'espaces.",
        'isDifferent'       => "Les champ %s sont différent",
        'validMdp'          => "Le champ %s doit être composé de 8|50 caractères, de minuscules, une majuscule de chiffres et d’au moins un caractère spécial",
        'validEmail'        => "Le champ %s n'est pas un email valide",
        'validTtile'        => "Le champ %s dois contenir 5|50 caractère (accent compris et espaces) et des (!|?)",
        'notEmpty'          => "Le champ %s ne doit pas être vide"
    ];
    
    public function __construct(string $key, string $rule, array $attributes = []){
        $this->key          = $key;
        $this->rule         = $rule;
        $this->attributes   = $attributes;
    }

    public function __toString()
    {
        $params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);
        return (string) call_user_func_array('sprintf', $params); 
    }

}
