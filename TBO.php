<?php

class TBO
{
    private $xml;
    public function __construct(){
        $this->xml = new DOMDocument("1.0", "UTF-8");
    }

    /**
     * @param $action
     * @param $arr_value
     * @return string
     */
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
        $xml_bdyreq = $this->xml->createElement("hot:$action"."Request");
        function addXmlElement($xml_bdyreq,$key,$value,$attr=null){
            $xml_bdyreqele = $this->xml->createElement("hot:$key",$value);
            if($attr){
                foreach($attr as $k => $v){
                    $xml_bdyreqele->setAttribute($k,$v);
                }
            }
            $xml_bdyreq->appendChild($xml_bdyreqele);
        }
        foreach ($arr_value as $key => $value ) {
            ////
            if(is_array($value['value'])){
                foreach($value['value'] as $key2 => $value2){

                }
            } else {
                addXmlElement($xml_bdyreq,$key,$value['value'],$value['attr']);
            }
            ////

        }


        $xml_bdy->appendChild($xml_bdyreq);
        $xml_env->appendChild($xml_bdy);

        $this->xml->appendChild($xml_env);
        $request = $this->xml->saveXML();

        $location = "http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc";
        $action = "http://TekTravel/HotelBookingApi/$action";
        $client = new SoapClient("http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc?wsdl");
        return $client->__doRequest($request, $location, $action, 2);


    }

    /**
     * @param $function
     * @param $arr_value
     */
    private function loadXML($function, $arr_value = []){
        $this->xml->loadXML($this->loadRequest($function,$arr_value));
    }


    /**
     * @param $arg
     * @return mixed
     */
    public function  DestinationCityList($arg){
        $this->loadXML(__FUNCTION__,$arg);
        $xml_res = $this->xml->getElementsByTagName('City');
        for($i = 0; $i < $xml_res->length; $i++) {

            $output = $xml_res->item($i)->attributes;

            $result[$i][$output->item(0)->name] = $output->item(0)->value;
            $result[$i][$output->item(1)->name] = $output->item(1)->value;
        }

        return $result;

    }

}
$new = new TBO();
$inp_arr = ["CountryCode"=>["value"=>"AE"]];
print_r($new->DestinationCityList($inp_arr));





