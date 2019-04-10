<?php

declare(strict_types=1);

namespace Announcements\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html
 *
 * @ORM\Entity
 * @ORM\Table(name="announcements")
 **/
class Announcement
{
    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $sort;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $content;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $is_active;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $modified;

    /**
     *
     * @return array|mixed
     */
    public function getAnnouncement(): array
    {
        return [
            'id' => $this->getId(),
            'sort' => $this->getSort(),
            'content' => $this->getContent(),
            'is_active' => $this->getIsActive(),
            'created' => $this->getCreated()->format('Y-m-d H:i:s'),
            'modified' => $this->getModified()->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param array $requestBody
     * @throws \Exception
     */
    public function setAnnouncement(array $requestBody): void
    {
        $this->setSort($requestBody['sort']);
        $this->setContent($requestBody['content']);
        $this->setModified(new \DateTime("now"));

        if (!isset($requestBody['is_active']))
        {
            $this->setIsActive(1);
        } else {
            $this->setIsActive($requestBody['is_active']);
        }
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getIsActive(): int
    {
        return $this->is_active;
    }

    /**
     * @param int $is_active
     */
    public function setIsActive(int $is_active): void
    {
        $this->is_active = $is_active;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @throws \Exception
     */
    public function setCreated(\DateTime $created = null): void
    {
        if (!$created && empty($this->getId())) {
            $this->created = new \DateTime("now");
        } else {
            $this->created = $created;
        }
    }

    /**
     * @return \DateTime
     */
    public function getModified(): \DateTime
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     * @throws \Exception
     */
    public function setModified(\DateTime $modified = null): void
    {
        if (!$modified) {
            $this->modified = new \DateTime("now");
        } else {
            $this->modified = $modified;
        }
    }
}
