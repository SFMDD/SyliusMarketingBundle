<?php

namespace FMDD\SyliusMarketingPlugin\Command;

use Doctrine\ORM\EntityManagerInterface;
use FMDD\SyliusMarketingPlugin\Entity\CartAbandoned;
use FMDD\SyliusMarketingPlugin\Entity\CartAbandonedSend;
use FMDD\SyliusMarketingPlugin\EventListener\NotificationOrderPayedListener;
use FMDD\SyliusMarketingPlugin\Repository\CartAbandonedSendRepository;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderPaymentTransitions;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

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

    protected function configure()
    {
        $this
            ->setName('NotificationOrderLoad')
            ->setDescription('Load previous order to notification');
    }

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        NotificationOrderPayedListener $notificationOrderPayedListener
    )
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
        $this->notificationOrderPayedListener = $notificationOrderPayedListener;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln("START : " . $this->getDescription());

        /** @var OrderInterface $order */
        foreach ($this->orderRepository->findAll() as $order) {
            $this->notificationOrderPayedListener->process($order);
            $io->writeln("Order " . $order->getNumber() . " added as notification");
        }

        $io->success('Finish');
        return 0;
    }
}
