<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliRepas
 *
 * @ORM\Table(name="ali_repas")
 * @ORM\Entity
 */
class AliRepas
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_aliment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idAliment;

    /**
     * @var int
     *
     * @ORM\Column(name="id_repas", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idRepas;

    /**
     * @return int
     */
    public function getIdRepas(): int
    {
        return $this->idRepas;
    }

    /**
     * @param int $idAliment
     */
    public function setIdAliment(int $idAliment): void
    {
        $this->idAliment = $idAliment;
    }

    /**
     * @param int $idRepas
     */
    public function setIdRepas(int $idRepas): void
    {
        $this->idRepas = $idRepas;
    }

    /**
     * @return int
     */
    public function getIdAliment(): int
    {
        return $this->idAliment;
    }

}
