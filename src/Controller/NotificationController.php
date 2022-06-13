<?php


namespace FMDD\SyliusMarketingPlugin\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use FMDD\SyliusMarketingPlugin\Entity\InstagramPost;
use FMDD\SyliusMarketingPlugin\Entity\Notification;
use FMDD\SyliusMarketingPlugin\Entity\NotificationUser;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

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
    /**
     * @var Environment
     */
    private Environment $templating;

    public function __construct(
        Registry $doctrine,
        DateTimeFormatter $dateTimeFormatter,
        Environment $templating
    ) {
        $this->doctrine = $doctrine;
        $this->dateTimeFormatter = $dateTimeFormatter;
        $this->templating = $templating;
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

                        $html = $this->generateNotificationTemplate($notification);

                        return new JsonResponse([
                            'error' => false,
                            'notification' => [
                                'template' => $html
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

    /**
     * TODO: Move to a tagged service, easier to add custom notification
     * @param Notification $notification
     * @return string
     */
    private function generateNotificationTemplate(Notification $notification) {
        switch($notification->getType()->getCode()) {
            case 'purchase':
                $product = $this->getDoctrine()->getRepository(ProductInterface::class)->find($notification->getOptions()['product_id']);
                if(is_null($product))
                    return '';
                return $this->templating->render('@FMDDSyliusMarketingPlugin/Notification/Type/_purchase.html.twig', [
                    'product' => $product,
                    'quantity' => $notification->getOptions()['quantity'],
                    'firstname' => $notification->getOptions()['firstname'],
                    'city' => $notification->getOptions()['city'],
                    'country' => $notification->getOptions()['country'],
                    'created_at' => $this->dateTimeFormatter->formatDiff($notification->getCreatedAt(), new \DateTime()),
                ]);
            case 'trustpilot':
                return $this->templating->render('@FMDDSyliusMarketingPlugin/Notification/Type/_trustpilot.html.twig', $notification->getOptions());
            case 'instagram':
                $posts = $this->getDoctrine()->getRepository(InstagramPost::class)->findAll();
                shuffle($posts);

                return $this->templating->render('@FMDDSyliusMarketingPlugin/Notification/Type/_instagram.html.twig', ['post' => $posts[0]]);
        }
    }
}
