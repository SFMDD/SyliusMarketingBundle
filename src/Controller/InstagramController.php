<?php


namespace FMDD\SyliusMarketingPlugin\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use FMDD\SyliusMarketingPlugin\Entity\Notification;
use FMDD\SyliusMarketingPlugin\Entity\NotificationUser;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Product\Model\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InstagramController extends AbstractController
{

    /** @var EntityRepository */
    private EntityRepository $instagramPostRepository;

    public function __construct(
        EntityRepository $instagramPostRepository
    ) {
        $this->instagramPostRepository = $instagramPostRepository;
    }

    public function showLatestAction(Request $request)
    {
        /** @var array $instagramPosts */
        $instagramPosts = $this->instagramPostRepository->findAll();
        shuffle($instagramPosts);
        $instagramPosts = array_slice($instagramPosts, 0, 4);

        return $this->render('@FMDDSyliusMarketingPlugin/Instagram/_instagram.html.twig', [
            'instagramPosts' => $instagramPosts
        ]);
    }
}
