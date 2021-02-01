<?php

namespace FMDD\SyliusMarketingPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fmdd_cart_abandoned_send")
 */
class CartAbandonedSend implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="order_id", length=12, nullable=true)
     */
    private $order;

    /**
     * @ORM\Column(type="integer", name="customer_id", length=12, nullable=true)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="FMDD\SyliusMarketingPlugin\Entity\CartAbandoned", inversedBy="cartAbandonedSends")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cartAbandoned;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="datetime", name="date_send")
     */
    private $dateSend;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getCustomer(): ?int
    {
        return $this->customer;
    }

    public function setCustomer(?int $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCartAbandoned(): ?CartAbandoned
    {
        return $this->cartAbandoned;
    }

    public function setCartAbandoned(?CartAbandoned $cartAbandoned): self
    {
        $this->cartAbandoned = $cartAbandoned;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(?string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDateSend(): ?\DateTimeInterface
    {
        return $this->dateSend;
    }

    public function setDateSend(\DateTimeInterface $dateSend): self
    {
        $this->dateSend = $dateSend;

        return $this;
    }
}
