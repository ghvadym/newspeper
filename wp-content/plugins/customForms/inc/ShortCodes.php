<?php

namespace inc;

class ShortCodes
{
    public function __construct() {
        add_shortcode( 'flexi_form' , [$this, 'FormShortcode'] );
        add_shortcode( 'flexi_entries' , [$this, 'EntriesShortcode'] );
    }
    
    public function FormShortcode() {
        Form::getTemplate();
    }
    
    public function EntriesShortcode() {
        Entries::getTemplate();
    }

}
