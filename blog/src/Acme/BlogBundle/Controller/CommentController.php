<?php

namespace Acme\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\BlogBundle\Entity\Comment;
use Acme\BlogBundle\Form\CommentType;

/**
 * @Route("/blog")
 */
class CommentController extends Controller
{
    /**
     * @Route("/{id}/comments", name="blog_comment_create")
     * @Method("post")
     */
    public function createAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AcmeBlogBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $comment = new Comment();
        $comment->setPost($entity);

        $request = $this->getRequest();
        $form    = $this->createForm(new CommentType(), $comment);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();

            return new Response('{}', 201, array('Content-Type' => 'application/json'));
            
        }

        return new Response('{}', 400, array('Content-Type' => 'application/json'));
    }
}
