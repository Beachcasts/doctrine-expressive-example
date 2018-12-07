<?php

declare(strict_types=1);

namespace Branches\Entity;

use Doctrine\ORM\Mapping as ORM;
use Banks\Entity\Bank;

/**
 * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/basic-mapping.html
 *
 * @ORM\Entity
 * @ORM\Table(name="branches")
 **/
class Branch
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * Many Branch will have one Bank
     *
     * @ORM\ManyToOne(targetEntity="Banks\Entity\Bank", inversedBy="branches")
     */
    protected $bank;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $bank_id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $zone_id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

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
     * @param bool $withBank
     * @return array
     */
    public function getBranch($withBank=false): array
    {
        $branch = [
            'id' => $this->getId(),
            'bank_id' => $this->getBankId(),
            'name' => $this->getName(),
            'address1' => $this->getAddress1(),
            'address2' => $this->getAddress2(),
            'zone_id' => $this->getZoneId(),
            'zip' => $this->getZip(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'is_active' => $this->getIsActive(),
            'created' => $this->getCreated()->format('Y-m-d H:i:s'),
            'modified' => $this->getModified()->format('Y-m-d H:i:s'),
        ];

        if ($withBank) {
            $branch['bank'] = $this->getBank();
        }

        return $branch;
    }

    /**
     * @param array $requestBody
     * @throws \Exception
     */
    public function setBranch(array $requestBody): void
    {
        $this->setBankId($requestBody['bank_id']);
        $this->setName($requestBody['name']);
        $this->setAddress1($requestBody['address1']);
        $this->setAddress2($requestBody['address2']);
        $this->setCity($requestBody['city']);
        $this->setZoneId($requestBody['zone_id']);
        $this->setZip($requestBody['zip']);
        $this->setEmail($requestBody['email']);
        $this->setPhone($requestBody['phone']);
        $this->setModified(new \DateTime("now"));

        if (!isset($requestBody['is_active']))
        {
            $this->setIsActive(1);
        } else {
            $this->setIsActive($requestBody['is_active']);
        }
    }

    /**
     * @return mixed|array
     */
    public function getBank(): array
    {
        return $this->bank->getBank();
    }

    /**
     * Used to create bank associations on Create
     *
     * @param $bank
     */
    public function setBank(Bank $bank): void
    {
        $this->bank = $bank;
    }

    /**
     * Removes Banks ManyToOne relationship to prevent infinite recursion
     *
     */
    public function resetBank(): void
    {
        $this->bank = ['see_parent'];
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
    public function getBankId(): int
    {
        return $this->bank_id;
    }

    /**
     * @param integer $bank_id
     */
    public function setBankId(int $bank_id): void
    {
        $this->bank_id = $bank_id;
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
    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     */
    public function setAddress1(?string $address1): void
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
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return int
     */
    public function getZoneId(): ?int
    {
        return $this->zone_id;
    }

    /**
     * @param int $zone_id
     */
    public function setZoneId(?int $zone_id): void
    {
        $this->zone_id = $zone_id;
    }

    /**
     * @return string
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
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
