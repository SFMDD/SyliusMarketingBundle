<?php


namespace FMDD\SyliusMarketingPlugin\Controller;

use FMDD\SyliusMarketingPlugin\Entity\InstagramPost;
use FMDD\SyliusMarketingPlugin\Provider\InstagramPostsProvider;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class InstagramController
{
    private Environment $twig;
    private InstagramPostsProvider $instagramPostsProvider;

    public function __construct(Environment $twig, InstagramPostsProvider $instagramPostsProvider)
    {
        $this->twig = $twig;
        $this->instagramPostsProvider = $instagramPostsProvider;
    }

    public function showLatestAction($max = 4)
    {
        $posts = $this->instagramPostsProvider->getPosts($max);
        return new Response(
            $this->twig->render('@FMDDSyliusMarketingPlugin/Instagram/_instagram.html.twig', [
                'instagramPosts' => $posts,
            ])
        );
    }

    public function display(InstagramPost $post)
    {
        return new Response(
            $this->twig->render('@FMDDSyliusMarketingPlugin/Instagram/_instagram_post.html.twig', [
                'post' => $this->instagramPostsProvider->display($post)
            ])
        );
    }
}
