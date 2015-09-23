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
    private function basic($action,$arr_value)
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

        foreach ($arr_value as $key => $value ) {
            $xml_bdyreqele = $this->xml->createElement("hot:$key",$value);
            $xml_bdyreq->appendChild($xml_bdyreqele);
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
        $this->xml->loadXML($this->basic($function,$arr_value));
    }

    /**
     * @param $countryCode
     * @return mixed
     */
    public function DestinationCityList($countryCode){

        $arr_value = ["CountryCode"=>$countryCode];
        $this->loadXML(__FUNCTION__,$arr_value);
        $xml_res = $this->xml->getElementsByTagName('City');
        for($i = 0; $i < $xml_res->length; $i++) {

            $output = $xml_res->item($i)->attributes;
            $length = $xml_res->item($i)->attributes->length;

            for($j = 0; $j < $length; $j++){
                $result[$i][$output->item($j)->name] = $output->item($j)->value;
            }
        }

        return !empty($result) ? $result : NULL ;

    }

    /**
     * @return mixed
     */
    public function TopDestinations() {

        $this->loadXML(__FUNCTION__);
        $xml_res = $this->xml->getElementsByTagName('City');

        for($i = 0; $i < $xml_res->length; $i++) {

            $output = $xml_res->item($i)->attributes;
            $length = $xml_res->item($i)->attributes->length;

            for($j = 0; $j < $length; $j++){
                $result[$i][$output->item($j)->name] = $output->item($j)->value;
            }

        }

        return !empty($result) ? $result : NULL ;
    }
}







