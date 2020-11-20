<?php


namespace FMDD\SyliusMarketingPlugin\Controller;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /** @var ProductRepositoryInterface */
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function redirectToProductAction($code) {
        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBy(['code' => $code]);
        return !is_null($product) ?
            $this->redirectToRoute('sylius_shop_product_show', ['slug' => $product->getSlug()]) :
            $this->redirectToRoute('sylius_shop_homepage');
    }
}