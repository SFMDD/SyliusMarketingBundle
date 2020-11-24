<?php

namespace FMDD\SyliusMarketingPlugin\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FMDD\SyliusMarketingPlugin\EventListener\NotificationOrderPayedListener;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderShippingStates;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Order\Model\OrderItemInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrustpilotExportDataCommand extends Command
{
    private static $DELIMITER = ',';
    protected static $defaultName = 'fmdd:trustpilot:export';

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var Registry
     */
    private Registry $doctrine;
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var CacheManager
     */
    private CacheManager $cacheManager;

    private $projectDir;
    /**
     * @var LocaleContextInterface
     */
    private LocaleContextInterface $localeContext;

    protected function configure()
    {
        $this
            ->setName('TrustpilotExportData')
            ->setDescription('Export data of clients order in csv to send them review with Trustpulot ');
    }

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Registry $doctrine,
        Router $router,
        CacheManager $cacheManager,
        LocaleContextInterface $localeContext,
        $projectDir
    )
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->cacheManager = $cacheManager;
        $this->localeContext = $localeContext;
        $this->projectDir = $projectDir;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln("START : " . $this->getDescription());

        $orders = $this->orderRepository->findAll();

        $data = '';
        /** @var OrderInterface $order */
        foreach ($orders as $order) {
            if($order->getPaymentState() === OrderPaymentStates::STATE_PAID && $order->getShippingState() === OrderShippingStates::STATE_SHIPPED) {
                $data .= $this->orderToString($order);
            }
        }

        $date = new \DateTime();
        $path = $this->projectDir . '\var\export\export_' . $date->format('Y-m-d_H-i-s') . '.csv';

        $file = fopen($path, "w+");
        fwrite($file, nl2br($data));
        fclose($file);

        $output->writeln($path);
        $io->success(sizeof($orders) . "orders exported");
        return 0;
    }

    private function orderToString(OrderInterface  $order)
    {
        $data = "";
        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {
            $customer = $order->getCustomer();
            $data .=
                $customer->getEmail() . self::$DELIMITER .
                $order->getShippingAddress()->getFullName() . self::$DELIMITER .
                $customer->getId() . self::$DELIMITER;
            $data .=
                $item->getVariant()->getCode() . self::$DELIMITER .
                (empty($item->getVariantName()) ? $item->getProductName() : $item->getVariantName()) . self::$DELIMITER .
                $this->router->generate(
                    'sylius_shop_product_show',
                    ['slug' => $item->getProduct()->getSlug(), '_locale' => $item->getProduct()->getTranslation()->getLocale()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ) . self::$DELIMITER .
                $this->cacheManager->generateUrl($item->getProduct()->getImages()->first()->getPath(), 'sylius_shop_product_thumbnail');
            $data .= PHP_EOL;
        }
        return $data;
    }
}
