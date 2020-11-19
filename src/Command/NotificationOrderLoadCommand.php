<?php

namespace FMDD\SyliusMarketingPlugin\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FMDD\SyliusMarketingPlugin\EventListener\NotificationOrderPayedListener;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NotificationOrderLoadCommand extends Command
{
    protected static $defaultName = 'fmdd:notification-order:load';

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var NotificationOrderPayedListener
     */
    private NotificationOrderPayedListener $notificationOrderPayedListener;
    /**
     * @var Registry
     */
    private Registry $doctrine;

    protected function configure()
    {
        $this
            ->setName('NotificationOrderLoad')
            ->setDescription('Load previous order to notification');
    }

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        NotificationOrderPayedListener $notificationOrderPayedListener,
        Registry $doctrine
    )
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
        $this->notificationOrderPayedListener = $notificationOrderPayedListener;
        $this->doctrine = $doctrine;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->removeNotifications($output);
        $output->writeln("START : " . $this->getDescription());

        $orders = $this->orderRepository->findAll();
        /** @var OrderInterface $order */
        foreach ($orders as $order) {
            $this->notificationOrderPayedListener->process($order);
        }
        $io->writeln(sizeof($orders) . "orders with added as notification");
        $io->success('Finish');
        return 0;
    }

    private function removeNotifications(OutputInterface $output)
    {
        $notifications = $this->doctrine->getRepository('FMDDSyliusMarketingPlugin:Notification')->findAll();
        $output->writeln("Remove notification : " . sizeof($notifications));
        $em = $this->doctrine->getManager();
        foreach ($notifications as $notification){
            $em->remove($notification);
        }
        $em->flush();
    }
}
