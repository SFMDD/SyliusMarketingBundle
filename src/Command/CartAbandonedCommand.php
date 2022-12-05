<?php

namespace FMDD\SyliusMarketingPlugin\Command;

use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ObjectManager;
use FMDD\SyliusMarketingPlugin\Entity\CartAbandoned;
use FMDD\SyliusMarketingPlugin\Entity\CartAbandonedSend;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CartAbandonedCommand extends Command
{
    protected static $defaultName = 'fmdd:cart-abandoned:run';

    private OutputInterface $output;

    private ObjectManager $manager;
    private OrderRepositoryInterface $orderRepository;
    private EntityRepository $cartAbandonedRepository;
    private EntityRepository $cartAbandonedSendRepository;

    private SenderInterface $sender;

    private array $emails = [];
    private string $environment;
    private string $cartExpirationPeriod;
    private string $orderExpirationPeriod;

    protected function configure()
    {
        $this
            ->setName('CartAbandonedCommand')
            ->setDescription('Send email for Cart Abandoned')
            ->addOption('recipients', 'r', InputOption::VALUE_REQUIRED, 'Recipients options : email@gmail.com,email@gmail.com');
    }

    public function __construct(
        Registry $doctrine,
        SenderInterface $sender,
        ParameterBagInterface $parameterBag,
        OrderRepositoryInterface $orderRepository,
        EntityRepository $cartAbandonedRepository,
        EntityRepository $cartAbandonedSendRepository
    ) {
        parent::__construct();
        $this->manager = $doctrine->getManager();
        $this->orderRepository = $orderRepository;
        $this->cartAbandonedRepository = $cartAbandonedRepository;
        $this->cartAbandonedSendRepository = $cartAbandonedSendRepository;
        $this->sender = $sender;
        $this->environment = $parameterBag->get('kernel.environment');
        $this->cartExpirationPeriod = $parameterBag->get('sylius_order.cart_expiration_period');
        $this->orderExpirationPeriod = $parameterBag->get('sylius_order.order_expiration_period');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->output = $output;

        $recipients = [];
        if($this->environment !== 'prod') {
            $option = $input->getOption('recipients');
            $recipients = explode(',', $input->getOption('recipients'));
            if(is_null($option) || empty($recipients)) {
                $io->error('Please add recipients addresses in the command arguments');
                return 0;
            }
        }

        $this->handleCarts();
        $this->sendEmails($io, $recipients);
        return 0;
    }

    private function handleCarts()
    {
        /** @var CartAbandoned $cartAbandoned */
        foreach ($this->cartAbandonedRepository->findBy(['status' => true]) as $cartAbandoned) {
            $date = new \DateTime('-' . $cartAbandoned->getSendDelay() . ' hours');
            $this->output->writeln("Start get orders before " . $date->format('d-m-Y H:i:s') . " for type : " . $cartAbandoned->getTemplate());

            switch($cartAbandoned->getTemplate()) {
                case 'no_payment':
                    $orders = $this->orderRepository->findOrdersUnpaidSince($date);
                    break;
                case 'no_checkout':
                    $orders = $this->orderRepository->findCartsNotModifiedSince($date);
                    break;
                default:
                    $orders = [];
            }
            $this->output->writeln("Found " . sizeof($orders) . " orders");
            $this->addOrderForEmail($orders, $cartAbandoned);
            $this->output->writeln('-----------------');
        }
    }

    private function addOrderForEmail($orders, CartAbandoned $cartAbandoned)
    {
        /** @var OrderInterface $order */
        foreach ($orders as $order) {
            if($order->getItems()->isEmpty()) continue;
            $number = empty($order->getNumber()) ? $order->getId() : $order->getNumber();
            $cartAbandonedSend = $this->cartAbandonedSendRepository->findOneBy(['order' => $order->getId(), 'cartAbandoned' => $cartAbandoned]);
            if (is_null($cartAbandonedSend)) {
                if(!is_null($order->getCustomer()) && !is_null($order->getCustomer()->getEmail()) && sizeof($order->getItems()) > 0) {
                    $earlier = is_null($order->getCheckoutCompletedAt()) ? $order->getCreatedAt() : $order->getCheckoutCompletedAt();
                    $later = new DateTime('now');
                    $difference = $earlier->diff($later)->format("%r%a");
                    if($difference < 0) $difference = 0;
                    $typePeriod = intval(str_replace( ' days', '', is_null($order->getCheckoutCompletedAt()) ? $this->cartExpirationPeriod : $this->orderExpirationPeriod));
                    $period = $typePeriod - $difference;

                    array_push($this->emails, [
                        'code' => $cartAbandoned->getTemplate(),
                        'recipients' => [$order->getCustomer()->getEmail()],
                        'data' => [
                            'channel' => $order->getChannel(),
                            'localeCode' => $order->getLocaleCode(),
                            'order' => $order,
                            'cartAbandoned' => $cartAbandoned,
                            'expiration_period' => $period,
                        ]
                    ]);

                    $cartAbandonedSend = new CartAbandonedSend();
                    $cartAbandonedSend->setCartAbandoned($cartAbandoned);
                    $cartAbandonedSend->setCustomer($order->getCustomer()->getId());
                    $cartAbandonedSend->setDateSend(new DateTime());
                    $cartAbandonedSend->setOrder($order->getId());
                    $this->manager->persist($cartAbandonedSend);
                    $this->output->writeln("Order #". $number . ' added email for ' . $cartAbandoned->getTemplate());
                } else {
                    $this->output->writeln("Order #". $number . ' ignored cause of no customer email available.');
                }
            } else {
                $this->output->writeln("Order #". $number . ' already get email for ' . $cartAbandoned->getTemplate());
            }
        }

        if($this->environment === 'prod') {
            $this->manager->flush();
        }
    }

    private function sendEmails($io, $recipients)
    {
        $this->output->writeln(sizeof($this->emails) . " emails are going to be sent.");
        foreach ($this->emails as $email) {
            $receivers = $this->environment === 'prod' ? $email['recipients'] : $recipients;
            $this->output->writeln('Send email to ' . implode(', ', $receivers)) . ' for template ' . $email['code'];
            $this->sender->send($email['code'], $receivers, $email['data']);
        }
        $this->emails = [];
        $io->success('All emails have been sent successfully.');
    }
}
