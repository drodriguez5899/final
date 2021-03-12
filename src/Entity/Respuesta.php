<?php

namespace App\Entity;

use App\Repository\RespuestaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RespuestaRepository::class)
 */
class Respuesta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenido;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagen;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="respuestas")
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Mensajes::class, inversedBy="respuestas")
     */
    private $mensajes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getMensajes(): ?Mensajes
    {
        return $this->mensajes;
    }

    public function setMensajes(?Mensajes $mensajes): self
    {
        $this->mensajes = $mensajes;

        return $this;
    }
}
