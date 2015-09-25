<?php

class TBO
{
    private $xml;
    private $result;
    public function __construct(){
        $this->xml = new DOMDocument("1.0", "UTF-8");
    }
    private function recursion($key,$value,&$xml_elem)
    {
        $attr = (isset($value['attr'])) ? $value['attr'] : null;
        $value['value'] = (isset($value['value'])) ? $value['value'] : '';
        if (is_array($value['value'])) {
            $xml_bdyreqele = $this->xml->createElement("hot:$key");
            if ($attr) {
                foreach ($attr as $k => $v) {
                    $xml_bdyreqele->setAttribute($k, $v);
                }
            }
            foreach ($value['value'] as $key2 => $value2) {
                $this->recursion($key2,$value2,$xml_bdyreqele);
            }
            $xml_elem->appendChild($xml_bdyreqele);
        } else {
            $xml_bdyreqele = $this->xml->createElement("hot:$key", $value['value']);
            if ($attr) {
                foreach ($attr as $k => $v) {
                    $xml_bdyreqele->setAttribute($k, $v);
                }
            }
            $xml_elem->appendChild($xml_bdyreqele);
        }
    }
    private function loadRequest($action,$arr_value)
    {
        $xml_env = $this->xml->createElement("soap:Envelope");
        $xml_env->setAttribute("xmlns:soap", "http://www.w3.org/2003/05/soap-envelope");
        $xml_env->setAttribute("xmlns:hot", "http://TekTravel/HotelBookingApi");

        /*create header*/
        $xml_hed = $this->xml->createElement("soap:Header");
        $xml_hed->setAttribute("xmlns:wsa", "http://www.w3.org/2005/08/addressing");

        $xml_cred = $this->xml->createElement("hot:Credentials");
        $xml_cred->setAttribute("UserName", "behtarin");
        $xml_cred->setAttribute("Password", "beht@123");

        $xml_wsaa = $this->xml->createElement("wsa:Action", "http://TekTravel/HotelBookingApi/$action");
        $xml_wsat = $this->xml->createElement("wsa:To", "http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc");

        $xml_hed->appendChild($xml_cred);
        $xml_hed->appendChild($xml_wsaa);
        $xml_hed->appendChild($xml_wsat);

        $xml_env->appendChild($xml_hed);

        /*create body*/
        $xml_bdy = $this->xml->createElement("soap:Body");
        $xml_bdyreq= $this->xml->createElement("hot:$action"."Request");


        foreach ($arr_value as $key => $value ) {
            $this->recursion($key,$value,$xml_bdyreq);
        }


        $xml_bdy->appendChild($xml_bdyreq);
        $xml_env->appendChild($xml_bdy);

        $this->xml->appendChild($xml_env);
        $request = $this->xml->saveXML();
        //print_r($request);

        $location = "http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc";
        $action = "http://TekTravel/HotelBookingApi/$action";
        $client = new SoapClient("http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc?wsdl");
        ///
        $this->result = $client->__doRequest($request, $location, $action, 2);
        ///
        return $this->result;


    }

    private function xmlstr_to_array($xmlstr) {
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        return $this->domnode_to_array($doc->documentElement);
    }
    private function domnode_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v) {
                        $output = (string) $v;
                    }
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

    private function array_key_search($array,$key){

        foreach($array as $k => $v){
            if($k == $key){
                $this->result = $array[$k];
                break;
            } else {
                if(is_array($v)){
                    $this->array_key_search($v,$key);
                }
            }
        }

    }

    private function responseTemplate($function,$arg=[]){
        $this->array_key_search($this->xmlstr_to_array($this->loadRequest($function,$arg)),$function.'Response');
    }
    public function  DestinationCityList($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  TopDestinations(){
        $this->responseTemplate(__FUNCTION__);
        return $this->result;
    }
    public function  CountryList(){
        $this->responseTemplate(__FUNCTION__);
        return $this->result;
    }
    public function  HotelSearch($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  HotelRoomAvailability($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  AvailabilityAndPricing($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  HotelBook($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  GenerateInvoice($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  Amendment($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  HotelCancel($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }
    public function  HotelDetails($arg){
        $this->responseTemplate(__FUNCTION__,$arg);
        return $this->result;
    }


}




