<?php

namespace FMDD\SyliusMarketingPlugin\Entity;

use FMDD\SyliusMarketingPlugin\Repository\CartAbandonedSendRepository;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity(repositoryClass=CartAbandonedSendRepository::class)
 * @ORM\Table(name="cart_abandoned_send")
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
     * @ORM\ManyToOne(targetEntity=Order::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Customer $customer;

    /**
     * @ORM\ManyToOne(targetEntity=CartAbandoned::class, inversedBy="cartAbandonedSends")
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

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
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
