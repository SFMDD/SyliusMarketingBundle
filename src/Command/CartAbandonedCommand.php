<?php

namespace FMDD\SyliusMarketingPlugin\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FMDD\SyliusMarketingPlugin\Entity\CartAbandoned;
use FMDD\SyliusMarketingPlugin\Entity\CartAbandonedSend;
use FMDD\SyliusMarketingPlugin\Repository\CartAbandonedSendRepository;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Mailer\Sender\Sender;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CartAbandonedCommand extends Command
{
    protected static $defaultName = 'fmdd:cart-abandoned:run';
    /**
     * @var EntityManager
     */
    private EntityManager $em;
    private array $emails;
    /**
     * @var Sender
     */
    private Sender $sender;
    /**
     * @var CartAbandonedSendRepository
     */
    private CartAbandonedSendRepository $cartAbandonedSendRepository;
    /**
     * @var EntityRepository
     */
    private EntityRepository $cartAbandonedRepository;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;
    private $output;

    protected function configure()
    {
        $this
            ->setDescription('Send email for Cart Abandoned');
    }

    public function __construct(
        EntityRepository $cartAbandonedRepository,
        CartAbandonedSendRepository $cartAbandonedSendRepository,
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $em,
        SenderInterface $sender,
        ParameterBagInterface $parameterBag)
    {
        $this->cartAbandonedRepository = $cartAbandonedRepository;
        $this->cartAbandonedSendRepository = $cartAbandonedSendRepository;
        $this->orderRepository = $orderRepository;
        $this->em = $em;
        $this->sender = $sender;
        $this->emails = [];
        $this->parameterBag = $parameterBag;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->output = $output;
        /**
         * STATE in cart :
         * Commander, mais non payé
         * Dans le panier mais non commencé le checkout
         */
        $crons = $this->cartAbandonedRepository->findAll();
        foreach ($crons as $cartAbandoned) {
            $this->cartNotPayed($cartAbandoned);
            //$this->cartNotCheckout($cartAbandoned);
            $this->sendEmails();
        }

        $io->success('Send email Finish');

        return 0;
    }

    private function cartNotPayed(CartAbandoned $cartAbandoned)
    {
        $this->output->writeln("START ORDER NO PAYED");
        $date = new \DateTime();
        $date->sub(new \DateInterval('PT'.$cartAbandoned->getSendDelay().'H'));
        //TODO: Request with join CartAbandonedSend
        $orders = $this->orderRepository->findOrdersUnpaidSince($date);
        $this->output->writeln("FIND : ".sizeof($orders)." orders");
        $this->addOrderForEmail($orders, $cartAbandoned);
    }

    private function cartNotCheckout(CartAbandoned $cartAbandoned)
    {
        $this->output->writeln("START ORDER NO CHECKOUT");
        $date = new \DateTime();
        $date->add(new \DateInterval('PT'.$cartAbandoned->getSendDelay().'H'));
        //TODO: Request with join CartAbandonedSend
        $orders = $this->orderRepository->findCartsNotModifiedSince($date);
        $this->output->writeln("FIND : ".sizeof($orders)." orders");
        $this->addOrderForEmail($orders, $cartAbandoned);
    }

    private function sendEmails()
    {
        foreach ($this->emails as $email) {
            $this->sender->send($email['code'], $email['recipients'], $email['data']);
        }
        $this->emails = [];
    }

    private function addOrderForEmail(array $orders, CartAbandoned $cartAbandoned)
    {
        /** @var Order $order */
        foreach ($orders as $order) {
            $cartAbandonedSend = $this->cartAbandonedSendRepository->findOneBy(['order' => $order, 'cartAbandoned' => $cartAbandoned]);
            if (is_null($cartAbandonedSend)) { //Remove si possible après la joitnure
                array_push($this->emails, [
                    'code' => $cartAbandoned->getTemplate(),
                    'recipients' => ['mathieu.delmarre@gmail.com'],
                    'data' => ['channel' => $order->getChannel(), 'localeCode' => $order->getLocaleCode(), 'order' => $order, 'cartAbandoned' => $cartAbandoned, 'cart_expiration_period' => $this->parameterBag->get('sylius_order.cart_expiration_period')]
                ]);
                $cartAbandonedSend = new CartAbandonedSend();
                $cartAbandonedSend->setCartAbandoned($cartAbandoned);
                $cartAbandonedSend->setCustomer($order->getCustomer());
                $cartAbandonedSend->setDateSend(new \DateTime());
                /** TODO: Set discount if generate code discount */
                //$cartAbandonnedSend->setDiscount();
                $cartAbandonedSend->setOrder($order);
                $this->em->persist($cartAbandonedSend);
                $this->output->writeln("SEND ORDER : #".$order->getNumber().".");
            }
        }
        $this->em->flush();
    }
}
