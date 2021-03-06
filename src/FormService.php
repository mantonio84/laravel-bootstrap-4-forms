<?php

namespace mantonio84\Bootstrap4Forms;

/**
 * FormService class
 *
 * @author neto
 */
class FormService {

    /**
     * The Form builder instance
     *
     * @var \mantonio84\Bootstrap4Forms\FormBuilder
     */
    private $_builder;

    /**
     * Render to be used
     *
     * @var string
     */
    private $_render;

    /**
     * Allowed renders
     *
     * @var array
     */
    private $_allowedRenders = ['open', 'close', 'file', 'text', 'range', 'password', 'email', 'number', 'hidden', 'select', 'checkbox', 'radio', 'textarea', 'button', 'submit', 'anchor', 'reset'];

    /**
     * Create a new FormSevice instance
     */
    public function __construct()
    {
        $this->_builder = new FormBuilder;
    }

    /**
     * Magic method to return a class string version
     *
     * @return string
     */
    public function __toString()
    {
        $output = '';

        if (in_array($this->_render, $this->_allowedRenders)) {

            $output = $this->_builder->{$this->_render}();
        }

        $this->_render = null;

        return $output;
    }

    /**
     * Open the form
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function open(): FormService
    {
        return $this->render('open');
    }

    /**
     * Close the form
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function close(): FormService
    {
        return $this->render('close');
    }

    /**
     * Set a prefix id for all inputs
     *
     * @param string $prefix
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function idPrefix(string $prefix = ''): FormService
    {
        return $this->_set('FidPrefix', $prefix);
    }

    /**
     * Set multipart attribute for a form
     *
     * @param bool $multipart
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function multipart(bool $multipart = true): FormService
    {
        return $this->_set('Fmultipart', $multipart);
    }

    /**
     * Set a method attribute for the form
     *
     * @param string $method
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function method(string $method): FormService
    {
        return $this->_set('Fmethod', $method);
    }

    /**
     * Set get method for the form attribute
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function get(): FormService
    {
        return $this->method('get');
    }

    /**
     * Set post method for the form attribute
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function post(): FormService
    {
        return $this->method('post');
    }

    /**
     * Set put method for the form attribute
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function put(): FormService
    {
        return $this->method('put');
    }

    /**
     * Set patch method for the form attribute
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function patch(): FormService
    {
        return $this->method('patch');
    }

    /**
     * Set delete method for the form attribute
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function delete(): FormService
    {
        return $this->method('delete');
    }

    /**
     * Fill the form values
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function fill($data): FormService
    {
        if (is_object($data)){                    
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }        
        }

        if (!is_array($data)) {
            $data = [];
        }

        return $this->_set('Fdata', $data);
    }

    /**
     * Set locale file for inputs translations
     *
     * @param string $path
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function locale(string $path): FormService
    {
        return $this->_set('Flocale', $path);
    }

    /**
     * Set inline form to inline inputs
     * @param bool $inline
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function inlineForm(bool $inline = true): FormService
    {
        if ($inline===true){
            $this->_set('FinlineForm', true);        
            return $this->_set('FhorizontalForm', false);
        }else{
            return $this->_set('FinlineForm', false);            
        }        
    }
    
    /**
     * Set inline form to inline inputs
     * @param bool $inline
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function horizontalForm($settings=false): FormService
    {
        if ($settings===false){                    
            return $this->_set('FhorizontalForm', false);
        }else if (is_array($settings)){
            $settings=array_change_key_case($settings,CASE_LOWER);
            if ((array_key_exists("label",$settings)) and (array_key_exists("fields",$settings))){
                if ((is_string($settings['label'])) and (is_string($settings['fields']))){
                    if ((!empty($settings['label'])) and (!empty($settings['fields']))){
                        $settings=array_intersect_key($settings,array_flip(["label","fields"]));
                        $settings['label']=strtolower(trim($settings['label']));
                        $settings['fields']=strtolower(trim($settings['fields']));
                        $this->_set('FinlineForm', false);                            
                        return $this->_set('FhorizontalForm', $settings);
                    }
                }            
            }
        }
        throw new \InvalidArgumentException("Settings must be a valid setup array (label/fields) or boolean 'false'!");        
    }

    /**
     * Set inline style for checkbox and radio inputs
     * @param bool $inline
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function inline(bool $inline = true): FormService
    {
        return $this->_set('checkInline', $inline);
    }

    /**
     * Set url for links and form action
     *
     * @param string $url
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function url(string $url): FormService
    {
        return $this->_set('url', url($url));
    }

    /**
     * Set route for links and form action
     *
     * @param string $route
     * @param array $params
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function route(string $route, array $params = []): FormService
    {
        return $this->_set('url', route($route, $params));
    }

    /**
     * Open a fieldset
     *
     * @param string $legend
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function fieldsetOpen(string $legend = null): FormService
    {
        return $this->_set('meta', ['legend' => $legend])->render('fieldsetOpen');
    }

    /**
     * Close a fieldset
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function fieldsetClose(): FormService
    {
        return $this->render('fieldsetClose');
    }

    /**
     * Set a help text
     *
     * @param string $text
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function help(string $text): FormService
    {
        return $this->_set('help', $text);
    }

    /**
     * Create a file input
     *
     * @param string $name
     * @param string $label
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function file(string $name = null, string $label = null): FormService
    {
        return $this->name($name)->label($label)->type('file');
    }

    /**
     * Create a text input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function text(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('text')->name($name)->label($label)->value($default);
    }
    
     /**
     * Create a number input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function number(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('number')->name($name)->label($label)->value($default);
    }

    /**
     * Create a range input
     *
     * @param string $name
     * @param string $label
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function range(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('range')->name($name)->label($label)->value($default);
    }

    /**
     * Create a hidden input
     *
     * @param string $name
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function hidden(string $name = null, string $default = null): FormService
    {
        return $this->name($name)->value($default)->type('hidden');
    }

    /**
     * Create a select input
     *
     * @param string $name
     * @param string $label
     * @param array $options
     * @param string|array $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function select(string $name = null, string $label = null, $options = [], $default = null): FormService
    {
        return $this->name($name)->label($label)->options($options)->value($default)->type('select');
    }

    /**
     * Set options for a select field
     *
     * @param mixed $options
     * @param string $valuesFieldName
     * * @param string $labelsFieldName
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function options($options = [], string $valuesFieldName = '', string $labelsFieldName=''): FormService
    {
        if (!empty($options)){
            $valuesFieldName=trim($valuesFieldName);
            $labelsFieldName=trim($labelsFieldName);
            if (($options instanceof \Illuminate\Database\Eloquent\Collection) and (!empty($valuesFieldName)) and (!empty($labelsFieldName))){
                $items=array();
                foreach ($collection as $md){
                    $k=$md->{$valuesFieldName};
                    $v=$md->{$labelsFieldName};
                    $items[$k]=$v;
                }
            }else if ((is_iterable($options)) and (!($options instanceof \Illuminate\Database\Eloquent\Collection))){
                $items=$options;   
            }
            if (isset($items)){
                return $this->_set('options', $items);
            }else{
                throw new \InvalidArgumentException("Invalid options given!");            
            }
        }
        return $this;
    }    

    /**
     * Create a checkbox input
     *
     * @param string $name
     * @param string $label
     * @param string $value
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function checkbox(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('checkbox', $name, $label, $value, $default);
    }

    /**
     * Create a radio input
     *
     * @param string $name
     * @param string $label
     * @param string $value
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function radio(string $name = null, string $label = null, string $value = null, string $default = null): FormService
    {
        return $this->_checkboxRadio('radio', $name, $label, $value, $default);
    }

    /**
     * Create a textarea input
     *
     * @param string $name
     * @param type $label
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function textarea(string $name = null, $label = null, string $default = null): FormService
    {
        return $this->type('textarea')->name($name)->value($default)->label($label);
    }
    
    
     /**
     * Sets autocomplete flag
     *     
     * @param bool $value
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function autocomplete($value=true){
        if ($value===true){
            return $this->_set("autocomplete","on");
        }else{
            return $this->_set("autocomplete","off");
        }
    }

    /**
     * Set a label
     *
     * @param type $label
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function label($label): FormService
    {
        return $this->_set('label', $label);
    }

    /**
     * Create a button
     *
     * @param string $value
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function button(string $value = null, $color = 'primary', $size = null): FormService
    {
        return $this->type('button')->color($color)->size($size)->value($value);
    }

    /**
     * Create a button type submit
     *
     * @param string $value
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function submit(string $value, $color = 'primary', $size = null): FormService
    {
        return $this->button($value)->type('submit')->color($color)->size($size);
    }

    /**
     * Create a button type reset
     *
     * @param string $value
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function reset(string $value, $color = 'primary', $size = null): FormService
    {
        return $this->button($value)->type('reset')->color($color)->size($size);
    }

    /**
     * Create a anchor
     *
     * @param string $value
     * @param type $url
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function anchor(string $value, $url = null): FormService
    {
        if ($url) {
            $this->url($url);
        }

        return $this->button($value)->type('anchor');
    }

    /**
     * Flag a checkbox or a radio input as checked
     *
     * @param bool $checked
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function checked(bool $checked = true): FormService
    {
        $type = $this->_builder->get('type');
        $meta = $this->_builder->get('meta');

        if ($type === 'radio' && $checked) {
            $checked = $meta['value'];
        }

        return $this->value($checked);
    }

    /**
     * Set a input value
     *
     * @param type $value
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function value($value = null): FormService
    {
        if ($value !== null) {
            return $this->_set('value', $value);
        }

        return $this;
    }

    /**
     * Set a input type
     *
     * @param type $type
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function type($type): FormService
    {
        return $this->_set('type', $type)->render($type);
    }

    /**
     * Set a render
     *
     * @param string $render
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function render(string $render): FormService
    {
        $this->_render = $render;

        return $this;
    }

    /**
     * Set a field id
     *
     * @param type $id
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function id($id): FormService
    {
        return $this->_set('id', $id);
    }

    /**
     * Set a field name
     *
     * @param type $name
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function name($name): FormService
    {
        return $this->_set('name', $name);
    }

    /**
     * Set the size
     *
     * @param string $size
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function size(string $size = null): FormService
    {
        return $this->_set('size', $size);
    }

    /**
     * Set the size as lg
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function lg(): FormService
    {
        return $this->size('lg');
    }

    /**
     * Set the size as sm
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function sm(): FormService
    {
        return $this->size('sm');
    }

    /**
     * Set the color
     *
     * @param string $color
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function color(string $color = null): FormService
    {
        return $this->_set('color', $color);
    }

    /**
     * Set primary color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function primary(): FormService
    {
        return $this->color('primary');
    }

    /**
     * Set secondary color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function secondary(): FormService
    {
        return $this->color('secondary');
    }

    /**
     * Set success color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function success(): FormService
    {
        return $this->color('success');
    }

    /**
     * Set danger color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function danger(): FormService
    {
        return $this->color('danger');
    }

    /**
     * Set warning color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function warning(): FormService
    {
        return $this->color('warning');
    }

    /**
     * Set info color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function info(): FormService
    {
        return $this->color('info');
    }

    /**
     * Set light color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function light(): FormService
    {
        return $this->color('light');
    }

    /**
     * Set dark color
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function dark(): FormService
    {
        return $this->color('dark');
    }

    /**
     * Set link style
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function link(): FormService
    {
        return $this->color('link');
    }

    /**
     * Set outline style
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function outline(bool $outline = true): FormService
    {
        return $this->_set('outline', $outline);
    }

    /**
     * Set block style
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function block(bool $status= true): FormService
    {
        return $this->_set('block', $status);
    }

    /**
     * Set readonly style
     *
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function readonly($status = true): FormService
    {
        return $this->_set('readonly', $status);
    }

    /**
     * Set the input disabled status
     *
     * @param type $status
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function disabled($status = true): FormService
    {
        return $this->_set('disabled', $status);
    }

    /**
     * Set the input required status
     *
     * @param type $status
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function required($status = true) : FormService
    {
        return $this->_set('required', $status);
    }

    /**
     * Set the input placeholder
     *
     * @param type $placeholder
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function placeholder($placeholder): FormService
    {
        return $this->_set('placeholder', $placeholder);
    }

    /**
     * Set custom attributes for a input
     *
     * @param mixed $attrName
     * @param mixed $attrValue
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function attrs($attrName, $attrValue=null): FormService
    {
       return $this->_setAttrsField("attrs",$attrName,$attrValue);
    }
    
    /**
     * Set custom data attribute for a input
     *
     * @param mixed $prefix
     * @param mixed $value
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    
    public function data($prefix, $value=null): FormService
    {
         if (is_array($prefix)){
            $a=array();
            foreach ($prefix as $k => $v) $a["data-".$k]=$v;
            return $this->attrs($a);
        }else if ((is_string($prefix)) and (is_string($value))){
            $a=array();
            $a["data-".$prefix]=$value;            
            return $this->attrs($a);
        }   
    }
    
     /**
     * Add a css class to input
     *
     * @param string $className     
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function withClass(string $className): FormService {
        $this->_builder->add_class("attrs",$className);
        return $this;
    }
    
    
    /**
     * Set custom attributes for wrapper div
     *
     * @param mixed $attrName
     * @param mixed $attrValue
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function attrsWrapper($attrName, $attrValue=null): FormService
    {
       return $this->_setAttrsField("attrsWrapper",$attrName,$attrValue);
    }
    
    
     /**
     * Add a css class to wrapper div
     *
     * @param string $className     
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function withClassOnWrapper(string $className): FormService {
        $this->_builder->add_class("attrsWrapper",$className);
        return $this;
    }
    
     /**
     * Set custom attributes for label
     *
     * @param mixed $attrName
     * @param mixed $attrValue
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function attrsLabel($attrName, $attrValue=null): FormService
    {
       return $this->_setAttrsField("attrsLabel",$attrName,$attrValue);
    }
    
    
     /**
     * Add a css class to label
     *
     * @param string $className     
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function withClassOnLabel(string $className): FormService {
        $this->_builder->add_class("attrsLabel",$className);
        return $this;
    }
    

    /**
     * Set a multiple select attribute
     *
     * @param bool $multiple
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    public function multiple(bool $multiple = true): FormService
    {
        return $this->_set('multiple', $multiple);
    }

    /**
     * Set a form builder attribute
     *
     * @param string $attr
     * @param mixed $value
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    private function _set($attr, $value): FormService
    {
        $this->_builder->set($attr, $value);

        return $this;
    }
    
    private function _setAttrsField($fieldName, $attrName, $attrValue){
        
        if (is_array($attrName)){
            return $this->_set($fieldName,$attrName);        
        }else if ((is_string($attrName)) and (is_string($attrValue))){
            $a=array();
            $a[$attrName]=$attrValue;
            return $this->_set($fieldName,$a);
        }
        return $this;
    }

    /**
     * Render a checkbox or a radio input
     *
     * @param string $type
     * @param string $name
     * @param string $label
     * @param mixed $value
     * @param string $default
     * @return \mantonio84\Bootstrap4Forms\FormService
     */
    private function _checkboxRadio($type, $name, $label, $value, $default): FormService
    {
        $inputValue = $value === null ? $name : $value;

        if ($default) {
            $default = $inputValue;
        }

        return $this->_set('meta', ['value' => $inputValue])->type($type)->name($name)->label($label)->value($default);
    }

}
