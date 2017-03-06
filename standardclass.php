<?php
class standardclass
{
	// some useful attribute
	public $var = "Testausgabe" .  "<br>";

	// first method for testing
	public function test_func() {
		echo $this->var;

        $file = '/home/dofeldsc/vips_aufgabentypen/vips_response/Elmar_response/example.xml';

		$xml_string = file_get_contents($file);

		$parse_result = $this->parse_proforma($xml_string);

	}


	public function parse_proforma($result_string) {

	    $xml = simplexml_load_string($result_string);
        $score = "";
        $validity = "";

        $result = array();

        // ToDo hier bindestrich wieder einfügen
        if(isset($xml->{graderengine})){
            $result['gradername'] = (string)$xml->{graderengine}['name'];
            $result['graderversion'] = (string)$xml->{graderengine}['version'];
        }

        if(isset($xml->result)){
            $result['score'] = (float)$xml->result->score;
            $result['validity'] = (float)$xml->result->validity;
        }


        // parse the single test results
        $i = 0;
        if(isset($xml->tests)){
            foreach($xml->tests->test as $cur_test) {
                $test_res = array();
                $test_res['id'] = (string)$cur_test['id'];

                //optional description
                if(isset($cur_test->title)) $test_res['title'] = (string)$cur_test->title;

                $test_res['score'] = (float)$cur_test->result->score;

                // ToDo Bindestrich
                $j = 0;
                if(isset($cur_test->{testfeedbacks})){
                    foreach($cur_test->{testfeedbacks}->feedback as $feed){
                        $feed_res = array();
                        $feed_res['audience'] = (string)$feed['audience'];
                        $feed_res['level'] = (string)$feed['level'];
                        $feed_res['class'] = (string)$feed['class'];
                        $feed_res['type'] = (string)$feed['type'];
                        $feed_res['value'] = (string)$feed;

                        $test_res["feedback_" . $j] = $feed_res;
                        $j++;
                    }
                }



                if(isset($cur_test->filerefs)) {
                    $fileref_res = array();
                    foreach($cur_test->filerefs->fileref as $ref){
                        array_push($fileref_res, (string)$ref['refid']);
                    }
                    $test_res['filerefs'] = $fileref_res;
                }
                $result["test_" . $i] = $test_res;
            }
        }


        if(isset($xml->attachments)) {
            $attach_list = array();

            foreach($xml->attachments->attachment as $attach){
                $attach_res = array();
                $attach_res['id'] = (string)$attach['id'];
                $attach_res['title'] = (string)$attach['title'];
                $attach_res['audience'] = (string)$attach['audience'];
                $attach_res['title'] = (string)$attach['title'];
                $attach_res['type'] = (string)$attach->file['type'];
                $attach_res['name'] = (string)$attach->file['name'];
                $attach_res['content'] = (string)$attach->file->content; // Todo checken was da alles rein kann

                array_push($attach_list, $attach_res);
            }
        }

        $result['attachments'] = $attach_res;

        print_r($result);

        return $result;

    }
}
?>