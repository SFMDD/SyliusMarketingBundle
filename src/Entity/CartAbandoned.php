<?php

namespace FMDD\SyliusMarketingPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="cart_abandoned")
 */
class CartAbandoned implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="integer", name="send_delay")
     */
    private $sendDelay;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $template;

    /**
     * @ORM\Column(type="boolean", name="target_active")
     */
    private $targetActive;

    /**
     * @ORM\Column(type="boolean", name="target_inactive")
     */
    private $targetInactive;

    /**
     * @ORM\Column(type="boolean", name="target_without_order")
     */
    private $targetWithoutOrder;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="discount_type")
     */
    private $discountType;

    /**
     * @ORM\Column(type="integer", nullable=true, name="discount_amount")
     */
    private $discountAmount;

    /**
     * @ORM\Column(type="integer", nullable=true, name="discount_validity")
     */
    private $discountValidity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=CartAbandonedSend::class, mappedBy="cartAbandoned", orphanRemoval=true)
     */
    private $cartAbandonedSends;

    public function __construct()
    {
        $this->cartAbandonedSends = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSendDelay(): ?int
    {
        return $this->sendDelay;
    }

    public function setSendDelay(int $sendDelay): self
    {
        $this->sendDelay = $sendDelay;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getTargetActive(): ?bool
    {
        return $this->targetActive;
    }

    public function setTargetActive(bool $targetActive): self
    {
        $this->targetActive = $targetActive;

        return $this;
    }

    public function getTargetInactive(): ?bool
    {
        return $this->targetInactive;
    }

    public function setTargetInactive(bool $targetInactive): self
    {
        $this->targetInactive = $targetInactive;

        return $this;
    }

    public function getTargetWithoutOrder(): ?bool
    {
        return $this->targetWithoutOrder;
    }

    public function setTargetWithoutOrder(bool $targetWithoutOrder): self
    {
        $this->targetWithoutOrder = $targetWithoutOrder;

        return $this;
    }

    public function getDiscountType(): ?string
    {
        return $this->discountType;
    }

    public function setDiscountType(string $discountType): self
    {
        $this->discountType = $discountType;

        return $this;
    }

    public function getDiscountAmount(): ?int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(int $discountAmount): self
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    public function getDiscountValidity(): ?int
    {
        return $this->discountValidity;
    }

    public function setDiscountValidity(int $discountValidity): self
    {
        $this->discountValidity = $discountValidity;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|CartAbandonedSend[]
     */
    public function getCartAbandonedSends(): Collection
    {
        return $this->cartAbandonedSends;
    }

    public function addCartAbandonedSend(CartAbandonedSend $cartAbandonedSend): self
    {
        if (!$this->cartAbandonedSends->contains($cartAbandonedSend)) {
            $this->cartAbandonedSends[] = $cartAbandonedSend;
            $cartAbandonedSend->setCartAbandoned($this);
        }

        return $this;
    }

    public function removeCartAbandonedSend(CartAbandonedSend $cartAbandonedSend): self
    {
        if ($this->cartAbandonedSends->contains($cartAbandonedSend)) {
            $this->cartAbandonedSends->removeElement($cartAbandonedSend);
            // set the owning side to null (unless already changed)
            if ($cartAbandonedSend->getCartAbandoned() === $this) {
                $cartAbandonedSend->setCartAbandoned(null);
            }
        }

        return $this;
    }
}
