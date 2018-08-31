<?php
namespace mantonio84\Bootstrap4Forms;

class AttributesContainer implements \ArrayAccess {
    
    private $data=array();
    public $suppressEmptyAttributes=false;
    
    public static function fast($props){
        $a=new AttributesContainer($props);
        return $a->render();
    }
    
    public function __construct($arr=null){
        if ($arr instanceof AttributesContainer) {
            $this->fromAnother($arr);
        }else if (is_array($arr)){
            $this->fromArray($arr);
        }else if (is_string($arr)){
            $this->fromString($arr);
        }
    }
    
    public function mergeWith($what){        
        if (!empty($what)){
            $a=new AttributesContainer($what);
            $this->data=array_merge($this->data,$a->toArray());
        }        
    }
    
    public function fromString(string $attribs){
        $attribs=trim(strtolower($attribs));
        $domDocument = new \DOMDocument();
        $domDocument->loadHTML("<div $attribs>");
        $domDocumentXPath = new \DOMXPath($domDocument);
        $nodes = $domDocumentXPath->query('//@*');
        $attributes = [];
        foreach ($nodes as $node) $attributes[$node->name] = trim($node->value);
        $this->data=$attributes;
    }
    
    public function fromAnother(AttributesContainer $o){
        $this->fromArray($o->toArray());
    }
    
    public function fromArray(array $arr){
        $this->data=$arr;
    }
    
    public function toArray(){
        return $this->data;
    }
    
    public function set(string $key, string $value){
        $key=strtolower(trim($key));        
        if ($key=="class"){                      
            $value=explode(" ",$value);        
            $value=array_filter($value,function ($a){
                return (strlen(trim($a))>0); 
            });                                      
            $value=array_values(array_unique($value));                
            $value=implode(" ",$value);            
        }else if ($key=="style"){
            $value=$this->explodeStyleValue($value);                            
            $value=$this->implodeStyleValue($value);            
        }
        if (!is_null($value)){
            $this->data[$key]=$value;
        }        
    }
    
    public function get(string $key){
        $key=strtolower(trim($key));
        if ($this->has($key)){
            $ret=$this->data[$key];
            if (strlen(trim($ret))>0){
                switch ($key){
                    case "class":
                        $ret.=" ";
                        break;
                    case "style":
                        $ret.=";";
                        break;
                }                    
            }
            return $ret;
        }
        return null;
    }
    
    public function remove(string $key){
        $key=strtolower(trim($key));
        if ($this->has($name)){
            unset($this->data[$name]);
        }   
    }
    
    public function has(string $key){
        $key=strtolower(trim($key));
        return array_key_exists($key,$this->data);
    }
    
    public function render(){
        if (empty($this->data)) return "";
        $ret=array();
        foreach ($this->data as $key => $value){
            $a=$key;
            if (!is_null($value)) {
                $a.="=\"".htmlspecialchars($value)."\"";
                
            }else{
                if ($this->suppressEmptyAttributes===true) continue;
            }
            $ret[]=$a;
        }
        return implode(" ",$ret);
    }   
    
    public function offsetSet($offset, $value) {
        $this->set($offset,$value);
    }

    public function offsetExists($offset) {
        return $this->has($offset);
    }

    public function offsetUnset($offset) {
        $this->remove($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    } 
    
    public function __toString(){
        return $this->render();
    }
    
    private function implodeStyleValue(array $css){
        $r=array();
        ksort($css);
        foreach ($css as $key => $value) $r[]=$key.":".$value;        
        return implode(";",$r);
    }
    
    private function explodeStyleValue(string $css){
        $results = array();
        preg_match_all("/([\w-]+)\s*:\s*([^;]+)\s*;?/", $css, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $match[1]=strtolower(trim($match[1]));
            $match[2]=trim($match[2]);
            $results[$match[1]] = $match[2];
        }                   
        return $results;
    }
}

?>