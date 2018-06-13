<?php
namespace App;

class Formulaire
{
    private $fieldName;
    private $fieldType;
    private $fieldPlaceHolder;
    private $fieldValue;
    private $fieldRequire;
    
    public function __construct($p_name, $p_type, $p_placeHolder = null, $p_value = null, $p_required= true)
    {
        $this->fieldName = $p_name;
        $this->fieldType = $p_type;
        $this->fieldPlaceHolder = $p_placeHolder;
        $this->fieldValue = $p_value;
        $this->fieldRequire = $p_required;
    }
    
  
    /**
     * 
     *   create and return the input field 
     * 
     */
    public function input()
    {
        return '<input type="'.$this->fieldType.'" name="'.$this->fieldName.'" placeholder="'.$this->fieldPlaceHolder?$this->fieldPlaceHolder: "".'" required="'.$this->fieldRequire.'" value="'.$this->fieldValue?$this->fieldValue:"".'" />';
    } 
    
    public function submit()
    {
        return '<input type="submit" name="'.$this->fieldName?$this->fieldName: "submit".'"  value="'.$this->fieldValue?$this->fieldValue:"submit".'">';
    }
        
}
