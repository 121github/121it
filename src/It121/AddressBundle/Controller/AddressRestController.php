<?php

namespace It121\AddressBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use It121\AddressBundle\Entity\OpenPostcode;
use It121\AddressBundle\Entity\PostcodeIo;
use It121\BackendBundle\Util\Util;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use It121\AddressBundle\Entity\PafPostcode;

class AddressRestController extends FOSRestController
{
	/**
	 * Get PAF Address by postcode
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Gets a PAF Address for a given postcode",
	 *   output = "It121\AddressBundle\Entity\PafPostcode",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the address is not found"
	 *   }
	 * )
	 *
	 //* @Annotations\View(template="BackendBundle:Address:pafShow.html.twig", templateVar="address")
	 *
	 * @param Request $request the request object
	 * @param string $postcode the address postcode
	 *
	 * @return array
	 *
	 * @throws NotFoundHttpException when address not exist
	 */
	public function getAddressPafAction($postcode)
	{
		$emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

		$address = $emukpostcodes->getRepository('AddressBundle:PafPostcode')->findBy(array('postcode' => $postcode));

		if (!$address) {
			throw new NotFoundHttpException(sprintf('The address \'%s\' was not found.',$postcode));
		}

		// Do something on pre update.
//		$client = $this->container->get('box_uk_postcodes_io.client');
//		$response = $client->lookup(array('postcode' => 'CF10 1DD'));

		return $address;
	}

	/**
	 * Get Open Maps Address by postcode
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Gets an open maps Address for a given postcode (using getAddress)",
	 *   output = "It121\AddressBundle\Entity\OpenPostcode",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the address is not found"
	 *   }
	 * )
	 *
	//* @Annotations\View(template="BackendBundle:Address:openShow.html.twig", templateVar="address")
	 *
	 * @param Request $request the request object
	 * @param string $postcode the address postcode
	 *
	 * @return array
	 *
	 * @throws NotFoundHttpException when address not exist
	 */
	public function getAddressOpenAction($postcode)
	{
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

        $postcode = Util::postcodeFormat($postcode);

        $openPostcodeList = $emukpostcodes->getRepository('AddressBundle:OpenPostcode')->findBy(array('postcode' => $postcode));

        $postcodeIo = $this->getPostcodeIo($postcode);

		if (!$openPostcodeList) {
            $response = $this->getAddressIo($postcode);

            $response = json_decode($response, true);

            if (isset($response['Message'])) {
                throw new NotFoundHttpException(sprintf($response['Message']));
            }

            else if(empty($response['Addresses'])) {
                throw new NotFoundHttpException(sprintf('No addresses \'%s\' were found.',$postcode));
            }

            foreach($response['Addresses'] as $address) {
                $openPostcode = new OpenPostcode();
                $openPostcode->setLatitude($response['Latitude']);
                $openPostcode->setLongitude($response['Longitude']);
                $openPostcode->setPostcode($postcode);
                $address_ar = explode(", ", $address);
                $openPostcode->setAdd1($address_ar[0]);
                $openPostcode->setAdd2($address_ar[1]);
                $openPostcode->setAdd3($address_ar[2]);
                $openPostcode->setAdd4($address_ar[3]);
                $openPostcode->setLocality($address_ar[4]);
                $openPostcode->setCity($address_ar[5]);
                $openPostcode->setCounty($address_ar[6]);

                $openPostcode->setPostcodeIo($postcodeIo);

                $emukpostcodes->persist($postcodeIo);
                $emukpostcodes->persist($openPostcode);
            }

            $emukpostcodes->flush();

            $openPostcodeList = $emukpostcodes->getRepository('AddressBundle:OpenPostcode')->findBy(array('postcode' => $postcode));
		}
        else {

            foreach($openPostcodeList as $openPostcode) {
                //if (!$openPostcode->getPostcodeIo()) {
                    $openPostcode->setPostcodeIo($postcodeIo);
                    $emukpostcodes->persist($openPostcode);
                    $emukpostcodes->persist($postcodeIo);
                //}
            }

            $emukpostcodes->flush();
        }

		return $openPostcodeList;
	}

	/**
	 * GetAddressIo API
	 */
	private function getAddressIo($postcode) {
        $postcode = str_replace(" ","",$postcode);
		$ch = curl_init();
		$getAddressIoUrl = ($this->container->getParameter('address')['get_address_io']['url']);
		$getAddressIokey = ($this->container->getParameter('address')['get_address_io']['key']);
		curl_setopt($ch, CURLOPT_URL, $getAddressIoUrl.$postcode.'?api-key='.$getAddressIokey);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response=curl_exec($ch);
		curl_close($ch);

		return $response;
	}

    /**
     * Get postcodeIo
     */
    private function getPostcodeIo($postcode) {
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');
        $client = $this->container->get('box_uk_postcodes_io.client');

        $postcodeIo = $emukpostcodes->getRepository('AddressBundle:PostcodeIo')->findOneBy(array('postcode' => $postcode));

        if (!$postcodeIo) {
            $postcodeIo = new PostcodeIo();
        }

        try{
            $response = $client->lookup(array('postcode' => $postcode));

            $postcodeIo->setPostcode($response['result']['postcode']);
            $postcodeIo->setQuality($response['result']['quality']);
            $postcodeIo->setEastings($response['result']['eastings']);
            $postcodeIo->setNorthings($response['result']['northings']);
            $postcodeIo->setCountry($response['result']['country']);
            $postcodeIo->setNhsHa($response['result']['nhs_ha']);
            $postcodeIo->setLongitude($response['result']['longitude']);
            $postcodeIo->setLatitude($response['result']['latitude']);
            $postcodeIo->setParliamentaryConstituency($response['result']['parliamentary_constituency']);
            $postcodeIo->setEuropeanElectoralRegion($response['result']['european_electoral_region']);
            $postcodeIo->setPrimaryCareTrust($response['result']['primary_care_trust']);
            $postcodeIo->setRegion($response['result']['region']);
            $postcodeIo->setLsoa($response['result']['lsoa']);
            $postcodeIo->setMsoa($response['result']['msoa']);
            $postcodeIo->setIncode($response['result']['incode']);
            $postcodeIo->setOutcode($response['result']['outcode']);
            $postcodeIo->setAdminDistrict($response['result']['admin_district']);
            $postcodeIo->setParish($response['result']['parish']);
            $postcodeIo->setAdminCounty($response['result']['admin_county']);
            $postcodeIo->setAdminWard($response['result']['admin_ward']);
            $postcodeIo->setCcg($response['result']['ccg']);
            $postcodeIo->setNuts($response['result']['nuts']);

        } catch(\Exception $e){

        }


        return $postcodeIo;
    }
}
