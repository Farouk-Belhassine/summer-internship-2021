<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ProdSteps
 *
 * @ORM\Table(name="prod_steps")
 * @ORM\Entity
 * @UniqueEntity("descp")
 * @UniqueEntity("color")
 */
class ProdSteps
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
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 750,
     *      maxMessage = "Step description cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="descp", type="string", length=750, nullable=false)
     */
    private $descp;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $color;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescp(): ?string
    {
        return $this->descp;
    }

    public function setDescp(?string $descp): self
    {
        $this->descp = $descp;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->descp;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
