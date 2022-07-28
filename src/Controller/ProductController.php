<?php


namespace FMDD\SyliusMarketingPlugin\Controller;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class ProductController
{
    private RouterInterface $router;
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        RouterInterface $router,
        ProductRepositoryInterface $productRepository
    ) {
        $this->router = $router;
        $this->productRepository = $productRepository;
    }

    public function redirectToProductAction($code) {
        $product = $this->productRepository->findOneBy(['code' => $code]);

        if(is_null($product)) {
            return new RedirectResponse($this->router->generate('sylius_shop_homepage'));
        } else {
            return new RedirectResponse($this->router->generate('sylius_shop_product_show', ['slug' => $product->getSlug()]));
        }
    }
}