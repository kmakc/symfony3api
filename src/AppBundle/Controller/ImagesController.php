<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ImagesController extends AbstractController
{

    use ControllerTrait;

    /**
     * @Rest\NoRoute()
     * @ParamConverter("image", converter="fos_rest.request_body",
     *      options={"deserializationContext"={"groups"={"Deserialize"}}}
     * )
     */
    public function postImagesAction(Image $image)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();

        return $this->view($image, Response::HTTP_CREATED)->setHeader(
            'Location',
            $this->generateUrl(
                'images_upload_put',
                ['image' => $image->getId()]
            )
        );
    }
}
