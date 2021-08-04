<?php


namespace FMDD\SyliusMarketingPlugin\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use FMDD\SyliusMarketingPlugin\Entity\InstagramPost;
use FMDD\SyliusMarketingPlugin\Entity\Notification;
use FMDD\SyliusMarketingPlugin\Entity\NotificationUser;
use FMDD\SyliusMarketingPlugin\Provider\InstagramPostsProvider;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Product\Model\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InstagramController extends AbstractController
{
    private InstagramPostsProvider $instagramPostsProvider;

    public function __construct(InstagramPostsProvider $instagramPostsProvider)
    {
        $this->instagramPostsProvider = $instagramPostsProvider;
    }

    public function showLatestAction($max = 4)
    {
        $posts = $this->instagramPostsProvider->getPosts($max);
        return $this->render('@FMDDSyliusMarketingPlugin/Instagram/_instagram.html.twig', [
            'instagramPosts' => $posts,
        ]);
    }

    public function display(InstagramPost $post) {
        return $this->instagramPostsProvider->display($post);
    }
}
