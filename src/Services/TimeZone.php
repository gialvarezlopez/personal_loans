<?php
namespace Services;
//namespace AppBundle\Services;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of test
 *
 * @author Owner
 */
/*
echo PPL_LANGx;
//require_once(__DIR__."/adauth/class.auth.php");
include_once(__DIR__."/paypal-express/class.connection.php");
include_once(__DIR__."/paypal-express/config.php");
include_once(__DIR__."/paypal-express/functions.php");
include_once(__DIR__."/paypal-express/paypal.class.php");
*/

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class TimeZone
{
	
	public function __construct()
	{
		//define('PPL_LANG', 'ES');
		
	}
    

    public function geoIP()
    {
        //https://ourcodeworld.com/articles/read/693/how-to-detect-the-city-country-and-locale-from-a-visitor-s-ip-using-the-free-geolite-database-in-symfony-3

        //IS CODE
        //http://kirste.userpage.fu-berlin.de/diverse/doc/ISO_3166.html

        // Declare the path to the GeoLite2-City.mmdb file (database)
        //$GeoLiteDatabasePath = $this->get('kernel')->getRootDir() . '/../web/GeoLite2-City/GeoLite2-City.mmdb';
        $GeoLiteDatabasePath =  '../web/GeoLite2-City/GeoLite2-City.mmdb';

        // Create an instance of the Reader of GeoIp2 and provide as first argument
        // the path to the database file
        $reader = new Reader($GeoLiteDatabasePath);

        try{
            // if you are in the production environment you can retrieve the
            // user's IP with $request->getClientIp()
            // Note that in a development environment 127.0.0.1 will
            // throw the AddressNotFoundException

            //var_dump($reader);
            // In this example, use a fixed IP address in Minnesota

            //$ip = "172.56.40.143"; //$_SERVER['REMOTE_ADDR'];
            $ip = $_SERVER['REMOTE_ADDR'];
            if( $ip == "127.0.0.1" || $ip == "::1" || $ip == "localhost")
            {
                $ip = "172.56.40.143";
            }
            $record = $reader->city($ip);

        } catch (AddressNotFoundException $ex) {
            // Couldn't retrieve geo information from the given IP
            //return new Response("It wasn't possible to retrieve information about the providen IP");
            return false;
        }

        //return new JsonResponse($record);
        return $record;
    }

	function fecha_local( $date, $zone, $format = 'Y-m-d H:i:s' ) {
        $datetime = $date;
        $utc = new \DateTime($datetime, new \DateTimeZone('UTC'));
        $utc->setTimezone(new \DateTimeZone($zone));
        return $utc->format('Y-m-d H:i:s');
    }

    function getTimeZone($date)
    {
        if( !empty($date) )
        {
            $geo = $this->geoIP();
            $zone = $this->getNameTimeZone();//$geo->location->timeZone;
            return $this->fecha_local($date, $zone);
        }
        
    }

    function getNameTimeZone()
    {
        $geo = $this->geoIP();
        return $zone = $geo->location->timeZone;
    }   
}
