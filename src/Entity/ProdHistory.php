<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use App\Entity\ProdSteps;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProdHistory
 *
 * @ORM\Table(name="prod_history")
 * @ORM\Entity
 */
class ProdHistory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_id", type="string", length=20, nullable=false)
     */
    private $prodId;

    /**
     * @var string
     *
     * @Assert\Type(type="alnum")
     * @ORM\Column(name="prod_serial", type="string", length=20, nullable=false)
     */
    private $prodSerial;

    /**
     * @var string
     *
     * @ORM\Column(name="stepid", type="string", nullable=false)
     */
    private $stepid;

    /**
     * @var string
     *
     * @ORM\Column(name="operatorid", type="string", nullable=false)
     */
    private $operatorid;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 750,
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="stepcomments", type="string", length=750, nullable=false)
     */
    private $stepcomments;

    /**
     * @var bool
     *
     * @ORM\Column(name="stepstatus", type="boolean", nullable=false)
     */
    private $stepstatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        if(is_string($this->timestamp)){
            $date = date('Y-m-d', time());
            return \DateTime::createFromFormat('Y-m-d', $date);
        }
        else{
            return $this->timestamp;
        }
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getProdId(): ?string
    {
        return $this->prodId;
    }

    public function setProdId(object $product): string
    {
        if ($product instanceof Product) {
            $this->prodId = $product->getProdid();
            return $product->getProdid();
        }
        return null;
    }

    public function getProdSerial(): ?string
    {
        return $this->prodSerial;
    }

    public function setProdSerial(string $prodSerial): self
    {
        $this->prodSerial = $prodSerial;

        return $this;
    }

    public function getStepid(): ?string
    {
        return $this->stepid;
    }

    public function setStepid(string $stepid): self
    {
        $this->stepid = $stepid;

        return $this;
    }

    public function getOperatorid(): ?string
    {
        return $this->operatorid;
    }

    public function setOperatorid(string $operatorid): self
    {
        $this->operatorid = $operatorid;

        return $this;
    }

    public function getStepcomments(): ?string
    {
        return $this->stepcomments;
    }

    public function setStepcomments(?string $stepcomments): self
    {
        $this->stepcomments = $stepcomments;

        return $this;
    }

    public function getStepstatus(): ?bool
    {
        return $this->stepstatus;
    }

    public function setStepstatus(bool $stepstatus): self
    {
        $this->stepstatus = $stepstatus;

        return $this;
    }
}
