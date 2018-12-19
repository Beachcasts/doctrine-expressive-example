<?php

declare(strict_types=1);

namespace Banks\Entity;

use Branches\Entity\Branch;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html
 *
 * @ORM\Entity(repositoryClass="BankRepository")
 * @ORM\Table(name="banks")
 **/
class Bank
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="parent_id", nullable=false)
     */
    protected $parent_id;

    /**
     * One Bank has Many Banks.
     * @ORM\OneToMany(targetEntity="Banks\Entity\Bank", mappedBy="parent")
     */
    private $children;

    /**
     * Many Banks have One Bank.
     * @ORM\ManyToOne(targetEntity="Banks\Entity\Bank", inversedBy="children")
     */
    private $parent;

    /**
     * One Bank could have Many Branches
     *
     * @ORM\OneToMany(targetEntity="Branches\Entity\Branch", mappedBy="bank")
     */
    protected $branches;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $fax;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $address1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $city;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $zone_id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $zip;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $product_1_price;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $product_2_price;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $allow_email_attachment;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $is_active;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modified;

    /**
     * Bank constructor.
     */
    public function __construct()
    {
        $this->branches = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * @param bool $withBranches
     * @param bool $withParent
     * @param bool $withChildren
     * @return array
     */
    public function getBank(bool $withBranches=false, bool $withParent=false, bool $withChildren=false): array
    {
        $bank = [
            'id' => $this->getId(),
            'parent_id' => $this->getParentId(),
            'name' => $this->getName(),
            'phone' => $this->getPhone(),
            'fax' => $this->getFax(),
            'address1' => $this->getAddress1(),
            'address2' => $this->getAddress2(),
            'zone_id' => $this->getZoneId(),
            'zip' => $this->getZip(),
            'product_1_price' => $this->getProduct1Price(),
            'product_2_price' => $this->getProduct2Price(),
            'allow_email_attachment' => $this->getAllowEmailAttachment(),
            'is_active' => $this->getIsActive(),
            'created' => $this->getCreated()->format('Y-m-d H:i:s'),
            'modified' => $this->getModified()->format('Y-m-d H:i:s'),
        ];

        if ($withBranches && count($this->branches) > 0) {
            $bank['branches'] = $this->withBranch($withBranches);
        }

        if ($withParent && !empty($this->parent->getId())) {
            $bank['parent'] = $this->getParent();
        }

        if ($withChildren && count($this->children) > 0) {
            $bank['children'] = $this->withChildren($withChildren);
        }

        return $bank;
    }

    /**
     * @param array $requestBody
     * @throws \Exception
     */
    public function setBank(array $requestBody): void
    {
        // required data fields
        $this->setName($requestBody['name']);
        $this->setAddress1($requestBody['address1']);
        $this->setCity($requestBody['city']);
        $this->setZoneId($requestBody['zone_id']);
        $this->setZip($requestBody['zip']);
        $this->setPhone($requestBody['phone']);
        $this->setFax($requestBody['fax']);
        $this->setProduct1Price($requestBody['product_1_price']);
        $this->setProduct2Price($requestBody['product_2_price']);
        $this->setAllowEmailAttachment($requestBody['allow_email_attachment']);
        $this->setModified(new \DateTime("now"));
        
        // optional data fields
        $this->setParentId($requestBody['parent_id'] ?? 1);
        $this->setAddress2($requestBody['address2'] ?? null);

        if (!isset($requestBody['is_active']))
        {
            $this->setIsActive(1);
        } else {
            $this->setIsActive($requestBody['is_active']);
        }
    }

    /**
     * @param $withBranches
     * @return mixed|array
     */
    public function withBranch(bool $withBranches): array
    {
        if ($withBranches) {
            return $this->getBranches()->map(function(Branch $branch) {
                // clears $branch->bank() to prevent infinite recursion
                $branch->resetBank();
                return $branch->getBranch(false);
            })->toArray();
        }

        return ['see_parent'];
    }

    /**
     * @return Collection
     */
    public function getBranches(): ?Collection
    {
        return $this->branches;
    }

    /**
     * @param $withChildren
     * @return mixed|array
     */
    public function withChildren(bool $withChildren): ?array
    {
        if ($withChildren) {
            return $this->getChildren()->map(function(Bank $bank) {
                return $bank->getBank(false,false, false);
            })->toArray();
        }

        return ['see_parent'];
    }

    /**
     * @return Collection
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getParent(): ?array
    {
        return $this->parent->getBank(false, false, false);
    }

    /**
     * Used to create bank parent associations on Create
     *
     * @param $bank
     */
    public function setParent($bank): void
    {
        $this->parent = $bank;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    /**
     * @param integer $parent_id
     */
    public function setParentId(?int $parent_id = 0): void
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax(string $fax): void
    {
        $this->fax = $fax;
    }

    /**
     * @return string
     */
    public function getAddress1(): string
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     */
    public function setAddress1(string $address1): void
    {
        $this->address1 = $address1;
    }

    /**
     * @return string
     */
    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     */
    public function setAddress2(?string $address2): void
    {
        $this->address2 = $address2;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return int
     */
    public function getZoneId(): int
    {
        return $this->zone_id;
    }

    /**
     * @param int $zone_id
     */
    public function setZoneId(int $zone_id): void
    {
        $this->zone_id = $zone_id;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return float
     */
    public function getProduct1Price(): float
    {
        return $this->product_1_price;
    }

    /**
     * @param float $product_1_price
     */
    public function setProduct1Price(float $product_1_price): void
    {
        $this->product_1_price = $product_1_price;
    }

    /**
     * @return float
     */
    public function getProduct2Price(): float
    {
        return $this->product_2_price;
    }

    /**
     * @param float $product_2_price
     */
    public function setProduct2Price(float $product_2_price): void
    {
        $this->product_2_price = $product_2_price;
    }

    /**
     * @return int
     */
    public function getAllowEmailAttachment(): int
    {
        return $this->allow_email_attachment;
    }

    /**
     * @param int $allow_email_attachment
     */
    public function setAllowEmailAttachment(int $allow_email_attachment): void
    {
        $this->allow_email_attachment = $allow_email_attachment;
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
