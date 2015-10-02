<?php

namespace It121\AddressBundle\Controller;

use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;

use It121\AddressBundle\Entity\PostcodeIo;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use It121\AddressBundle\Entity\PafPostcode;

class AddressRestController extends FOSRestController
{

	/**
	 * Get Address by postcode
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Gets an Address for a given postcode",
	 *   output = "It121\AddressBundle\Entity\PafPostcode",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the address is not found"
	 *   }
	 * )
	 *
//	 * @Annotations\View(template="BackendBundle:Address:pafShow.html.twig", templateVar="address")
	 *
	 * @param Request $request the request object
	 * @param string $postcode the address postcode
	 *
	 * @return array
	 *
	 * @throws NotFoundHttpException when address not exist
	 */
	public function getAddressAction($postcode)
	{
		$emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

		$address = $emukpostcodes->getRepository('AddressBundle:PafPostcode')->findBy(array('postcode' => $postcode));

		if (!$address) {
			throw new NotFoundHttpException(sprintf('The address \'%s\' was not found.',$postcode));
		}

		// Do something on pre update.
		$client = $this->container->get('box_uk_postcodes_io.client');
		$response = $client->lookup(array('postcode' => 'CF10 1DD'));

		return $address;
	}
}
