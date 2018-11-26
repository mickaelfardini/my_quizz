<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anwser
 *
 * @ORM\Table(name="anwser")
 * @ORM\Entity
 */
class Anwser
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
     * @var int|null
     *
     * @ORM\Column(name="id_question", type="integer", nullable=true)
     */
    private $idQuestion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="anwser", type="string", length=255, nullable=true)
     */
    private $anwser;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="expected", type="boolean", nullable=true)
     */
    private $expected;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdQuestion(): ?int
    {
        return $this->idQuestion;
    }

    public function setIdQuestion(?int $idQuestion): self
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }

    public function getAnwser(): ?string
    {
        return $this->anwser;
    }

    public function setAnwser(?string $anwser): self
    {
        $this->anwser = $anwser;

        return $this;
    }

    public function getExpected(): ?bool
    {
        return $this->expected;
    }

    public function setExpected(?bool $expected): self
    {
        $this->expected = $expected;

        return $this;
    }


}
