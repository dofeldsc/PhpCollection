<?php
class standardclass
{
	// some useful attribute
	public $var = 'Testausgabe';

	// first method for testing
	public function test_func() {
		echo $this->var;
	}


	public function parse_proforma($result_string) {
	    $xml = simplexml_load_string($result_string);

	    $percent = "";
	    $comment = "";
	    $output  = "";



    }
}
?>