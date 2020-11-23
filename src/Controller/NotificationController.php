<?php


namespace FMDD\SyliusMarketingPlugin\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
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

class NotificationController extends AbstractController
{
    /**
     * @var Registry
     */
    private Registry $doctrine;
    /**
     * @var DateTimeFormatter
     */
    private DateTimeFormatter $dateTimeFormatter;

    public function __construct(
        Registry $doctrine,
        DateTimeFormatter $dateTimeFormatter
    ) {
        $this->doctrine = $doctrine;
        $this->dateTimeFormatter = $dateTimeFormatter;
    }

    public function randomAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            /** @var EntityManagerInterface $em */
            $em = $this->doctrine->getManager();

            try {
                $query = $em->createQueryBuilder()
                    ->select('a')
                    ->from('FMDDSyliusMarketingPlugin:Notification', 'a')
                    ->leftJoin('FMDDSyliusMarketingPlugin:NotificationUser', 'b', Join::WITH, 'b.notification = a.id AND b.ip = :ip')
                    ->leftJoin('FMDDSyliusMarketingPlugin:NotificationType', 't', Join::WITH, 't.id = a.type')
                    ->where('b.notification IS NULL')
                    ->andWhere("t.enabled = 1")
                    ->orderBy('a.createdAt', 'DESC')
                    ->setParameter('ip', $request->getClientIp())
                    ->setMaxResults(1)
                    ->getQuery();

                /** @var Notification $notification */
                $notification = $query->getSingleResult();

                if ($notification->getType()->getEnabled()) {
                    if (!is_null($notification)) {
                        $notificationUser = new NotificationUser();
                        $notificationUser->setNotification($notification);
                        $notificationUser->setIp($request->getClientIp());
                        $notificationUser->setCreatedAt(new \DateTime());
                        $em->persist($notificationUser);
                        $em->flush();

                        return new JsonResponse([
                            'error' => false,
                            'notification' => [
                                'type' => $notification->getType()->getCode(),
                                'options' => $notification->getOptions(),
                                'created_at' => $this->dateTimeFormatter->formatDiff($notification->getCreatedAt(), new \DateTime()),
                            ],
                        ]);
                    }
                }
            } catch (\Exception $e) {
                return new JsonResponse([
                    'error' => true,
                    'notification' => null,
                    'exception' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'line' => $e->getLine(),
                ]);
            }
        }
        return new Response('', 404);
    }
}
