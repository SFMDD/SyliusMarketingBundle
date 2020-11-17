<?php


namespace FMDD\SyliusMarketingPlugin\Controller;


use App\Entity\Notification;
use App\Entity\NotificationUser;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Sylius\Component\Core\Model\ShopUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends AbstractController
{
    public function __construct() {

    }

    public function randomAction(Request $request, Registry $doctrine)
    {
        /** @var EntityManagerInterface $em */
        $em = $doctrine->getManager();

        try {
            /** @var ShopUser|null $user */
            $user = is_null($this->getUser()) ? null : $this->getUser();

            /** @var Notification $notification */
            $notification = $em->createQueryBuilder()
                ->select('a')
                ->from('App:Notification', 'a')
                ->leftJoin('App:NotificationUser', 'b',Join::WITH, 'b.notification = a.id AND (b.user = :user OR b.ip = :ip)')
                ->where('b.notification IS NULL')
                ->setParameter('user', $user)
                ->setParameter('ip', $request->getClientIp())
                ->getQuery()
                ->getSingleResult();

            if (!is_null($notification)) {
                $notificationUser = new NotificationUser();
                $notificationUser->setNotification($notification);
                $notificationUser->setUser($user);
                $notificationUser->setIp($request->getClientIp());
                $notificationUser->setCreatedAt(new \DateTime());
                $em->persist($notificationUser);
                $em->flush();
            }

            return new JsonResponse([
                'error' => false,
                'notification' => [
                    'type' => $notification->getType()->getCode(),
                    'options' => json_decode($notification->getOptions()),
                    'created_at' => $notification->getCreatedAt(),
                ],
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => true,
                'notification' => null
            ]);
        }
    }
}