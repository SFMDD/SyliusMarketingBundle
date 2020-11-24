<?php

namespace FMDD\SyliusMarketingPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="fmdd_instagram_post")
 */
class InstagramPost implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="author")
     */
    private $author;

    /**
     * @ORM\Column(type="text", name="content")
     */
    private $content;

    /**
     * @ORM\Column(type="text", name="image_small")
     */
    private $imageSmall;

    /**
     * @ORM\Column(type="text", name="image_medium")
     */
    private $imageMedium;

    /**
     * @ORM\Column(type="text", name="image_big")
     */
    private $imageBig;

    /**
     * @ORM\Column(type="integer", name="comments")
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", name="likes")
     */
    private $likes;

    /**
     * @ORM\Column(type="string", name="link")
     */
    private $link;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getImageSmall()
    {
        return $this->imageSmall;
    }

    /**
     * @param mixed $imageSmall
     */
    public function setImageSmall($imageSmall): void
    {
        $this->imageSmall = $imageSmall;
    }

    /**
     * @return mixed
     */
    public function getImageMedium()
    {
        return $this->imageMedium;
    }

    /**
     * @param mixed $imageMedium
     */
    public function setImageMedium($imageMedium): void
    {
        $this->imageMedium = $imageMedium;
    }

    /**
     * @return mixed
     */
    public function getImageBig()
    {
        return $this->imageBig;
    }

    /**
     * @param mixed $imageBig
     */
    public function setImageBig($imageBig): void
    {
        $this->imageBig = $imageBig;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes): void
    {
        $this->likes = $likes;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
