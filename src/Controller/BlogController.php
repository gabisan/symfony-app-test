<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 *
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 10);

        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items      = $repository->findAll();

//        return new Response($limit);
        return $this->json([
                'page' => $page,
                'limit' => $limit,
                'data' => $items
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function post($id)
    {
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
        );
    }

//    /**
//     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"})
//     */
//    public function post(BlogPost $post)
//    {
//        return $this->json($post);
//    }

//    /**
//     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"})
//     * @ParamConverter("post", class="App:BlogPost")
//     */
//    public function post($post)
//    {
//        return $this->json($post);
//    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     */
    public function postBySlug($slug)
    {
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->findOneBy(['slug' => $slug])
        );
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();

        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);

    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(BlogPost $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}