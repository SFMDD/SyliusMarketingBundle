<?php

namespace FMDD\SyliusMarketingPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="fmdd_notification")
 */
class Notification implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="FMDD\SyliusMarketingPlugin\Entity\NotificationType", inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="json")
     */
    private $options;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=NotificationUser::class, mappedBy="notification", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $notificationsUsers;

    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->notificationsUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?NotificationType
    {
        return $this->type;
    }

    public function setType(?NotificationType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function addNotificationsUsers(NotificationUser $notificationUser): self
    {
        if (!$this->notificationsUsers->contains($notificationUser)) {
            $this->notificationsUsers[] = $notificationUser;
            $notificationUser->setNotification($this);
        }

        return $this;
    }

    public function removeCartAbandonedSend(NotificationUser $notificationUser): self
    {
        if ($this->notificationsUsers->contains($notificationUser)) {
            $this->notificationsUsers->removeElement($notificationUser);
            // set the owning side to null (unless already changed)
            if ($notificationUser->getNotification() === $this) {
                $notificationUser->setNotification(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getNotificationsUsers(): ArrayCollection
    {
        return $this->notificationsUsers;
    }

    /**
     * @param ArrayCollection $notificationsUsers
     */
    public function setNotificationsUsers(ArrayCollection $notificationsUsers): void
    {
        $this->notificationsUsers = $notificationsUsers;
    }
}
