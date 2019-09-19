<?php

/**
 * testing...
 */
class cohort {
  var $days = array();

}

class person {
  var $name;
  function set_name($new_name) { 
    $this->name = $new_name;
  }
  function get_name() {
   return $this->name;
  }
}

$this_person = new person();
$this_person->set_name("charlie");
var_dump($this_person);
$cohort = new cohort();

var_dump($cohort);



//phpinfo();
