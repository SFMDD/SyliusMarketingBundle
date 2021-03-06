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
    /**
     * @var ObjectManager
     */
    private ObjectManager $em;
    private array $emails;
    /**
     * @var SenderInterface
     */
    private SenderInterface $sender;
    /**
     * @var EntityRepository
     */
    private EntityRepository $cartAbandonedRepository;
    /**
     * @var EntityRepository
     */
    private EntityRepository $cartAbandonedSendRepository;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;
    private OutputInterface $output;
    private Registry $doctrine;

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
    )
    {
        $this->cartAbandonedRepository = $cartAbandonedRepository;
        $this->cartAbandonedSendRepository = $cartAbandonedSendRepository;
        $this->orderRepository = $orderRepository;
        $this->em = $doctrine->getManager();
        $this->doctrine = $doctrine;
        $this->sender = $sender;
        $this->emails = [];
        $this->parameterBag = $parameterBag;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->output = $output;
        $this->output->writeln("START : ".$this->getDescription()." ENV : ". $this->parameterBag->get('kernel.environment'));
        $recipients = explode(',', $input->getOption('recipients'));

        /**
         * STATE in cart :
         * Commander, mais non payé
         * Dans le panier mais non commencé le checkout
         */
        /** @var CartAbandoned $cartAbandoned */
        foreach ($this->cartAbandonedRepository->findBy(['status' => true]) as $cartAbandoned) {
            if($cartAbandoned->getCartNotPayed())
                $this->cartNotPayed($cartAbandoned);
            if($cartAbandoned->getCartNotCheckout())
                $this->cartNotCheckout($cartAbandoned);
        }

        $io->writeln(sizeof($this->emails) . "Emails waiting for send");
        $this->sendEmails($recipients);
        $io->success('Send email Finish');

        return 0;
    }

    private function cartNotPayed(CartAbandoned $cartAbandoned)
    {
        $this->output->writeln("START ORDER NO PAYED");
        $date = new DateTime();
        $date->sub(new DateInterval('PT'.$cartAbandoned->getSendDelay().'H'));
        $orders = $this->orderRepository->findOrdersUnpaidSince($date);
        $this->output->writeln("FIND : ".sizeof($orders)." orders");
        $this->addOrderForEmail($orders, $cartAbandoned);
    }

    private function cartNotCheckout(CartAbandoned $cartAbandoned)
    {
        $this->output->writeln("START ORDER NO CHECKOUT");
        $date = new DateTime();
        $date->add(new DateInterval('PT'.$cartAbandoned->getSendDelay().'H'));
        $orders = $this->orderRepository->findCartsNotModifiedSince($date);
        $this->output->writeln("FIND : ".sizeof($orders)." orders");
        $this->addOrderForEmail($orders, $cartAbandoned);
    }

    private function addOrderForEmail($orders, CartAbandoned $cartAbandoned)
    {
        $this->output->writeln("#START#".$cartAbandoned->getSubject()."-".$cartAbandoned->getTemplate()."-".$cartAbandoned->getSendDelay());
        /** @var OrderInterface $order */
        foreach ($orders as $order) {
            $this->output->write("ORDER : ". $order->getNumber() . " " . $order->getId());
            $cartAbandonedSend = $this->cartAbandonedSendRepository->findOneBy(['order' => $order->getId(), 'cartAbandoned' => $cartAbandoned]);
            if (is_null($cartAbandonedSend)) {
                if(!is_null($order->getCustomer()) and !is_null($order->getCustomer()->getEmail()) and sizeof($order->getItems()) > 0
                    and !$this->findIfOrderValidBetween1hours($order)){
                    array_push($this->emails, [
                        'code' => $cartAbandoned->getTemplate(),
                        'recipients' => [$order->getCustomer()->getEmail()],
                        'data' => [
                            'channel' => $order->getChannel(),
                            'localeCode' => $order->getLocaleCode(),
                            'order' => $order,
                            'cartAbandoned' => $cartAbandoned,
                            'cart_expiration_period' => $this->parameterBag->get('sylius_order.cart_expiration_period'),
                            'order_expiration_period' => $this->parameterBag->get('sylius_order.order_expiration_period')
                        ]
                    ]);
                }
                $cartAbandonedSend = new CartAbandonedSend();
                $cartAbandonedSend->setCartAbandoned($cartAbandoned);
                if(!is_null($order->getCustomer()))
                    $cartAbandonedSend->setCustomer($order->getCustomer()->getId());
                $cartAbandonedSend->setDateSend(new DateTime());
                /** TODO: Set discount if generate code discount */
                //$cartAbandonnedSend->setDiscount();
                $cartAbandonedSend->setOrder($order->getId());
                $this->em->persist($cartAbandonedSend);
                $this->output->writeln("SEND ORDER : #".$order->getNumber().".");
            }
        }
        if($this->parameterBag->get('kernel.environment') != 'dev') //Not Register CartAbandonedSend in dev for testing
            $this->em->flush();
        $this->output->writeln("#FINISH");
    }

    private function sendEmails($recipients)
    {
        foreach ($this->emails as $email) {
            if($this->parameterBag->get('kernel.environment') != 'dev') //Email customer, without in dev
                try{
                    $this->sender->send($email['code'], $email['recipients'], $email['data']);
                } catch (\Exception $e){

                }
            if(!empty($recipients)) //Email admin
                try {
                    $this->sender->send($email['code'], $recipients, $email['data']);
                } catch (\Exception $e){
                }

        }
        $this->emails = [];
    }

    private function findIfOrderValidBetween1hours(OrderInterface $order)
    {
        $builder = $this->orderRepository->createCartQueryBuilder();
        $dateCompleteAtStart = $order->getCheckoutCompletedAt()->modify('-1 hours');
        $dateCompleteAtEnd = $order->getCheckoutCompletedAt()->modify('+1 hours');

        return !is_null($builder->select('s')
            ->where('s.user = ?1')
            ->andWhere('s.checkoutCompletedAt >= ?2')
            ->andWhere('s.checkoutCompletedAt <= ?3')
            ->andWhere("s.paymentState = 'paid'")
            ->setParameter(1, $order->getCustomer()->getUser())
            ->setParameter(2, $dateCompleteAtStart)
            ->setParameter(3, $dateCompleteAtEnd)->getQuery()->getFirstResult());
    }
}
