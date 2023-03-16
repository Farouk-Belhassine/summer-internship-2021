<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 * @UniqueEntity("prodid")
 */
class Product
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
     * @Assert\Type(type="alnum")
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="ProdId", type="string", length=20, nullable=false)
     */
    private $prodid; //lettre et chiffres

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 250,
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(name="descp", type="string", length=250, nullable=false)
     */
    private $descp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdid(): ?string
    {
        return $this->prodid;
    }

    public function setProdid(?string $prodid): self
    {
        $this->prodid = $prodid;

        return $this;
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

    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->prodid;
        // to show the id of the Category in the select
        // return $this->id;
    }

}
