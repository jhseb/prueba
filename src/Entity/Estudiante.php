<?php

namespace App\Entity;

use App\Repository\EstudianteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: EstudianteRepository::class)]
#[ORM\Table(name: '`Estudiante`')]
class Estudiante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $identidad = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 50)]
    private ?string $salon = null;

    #[ORM\Column(length: 100)]
    private ?string $acudiente = null;

    #[ORM\Column]
    private ?int $edad = null;

    #[ORM\Column(length: 20)]
    private ?string $genero = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentidad(): ?string
    {
        return $this->identidad;
    }

    public function setIdentidad(string $identidad): static
    {
        $this->identidad = $identidad;
        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getSalon(): ?string
    {
        return $this->salon;
    }

    public function setSalon(string $salon): static
    {
        $this->salon = $salon;
        return $this;
    }

    public function getAcudiente(): ?string
    {
        return $this->acudiente;
    }

    public function setAcudiente(string $acudiente): static
    {
        $this->acudiente = $acudiente;
        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): static
    {
        $this->edad = $edad;
        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;
        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('identidad', new Assert\NotBlank(['message' => 'Este campo no puede estar vacío']));
        $metadata->addPropertyConstraint('identidad', new Assert\Length([
            'min' => 10,
            'max' => 10,
            'exactMessage' => 'La cédula debe tener exactamente 10 dígitos',
        ]));
        $metadata->addPropertyConstraint('identidad', new Assert\Regex([
            'pattern' => '/^\d+$/',
            'message' => 'La cédula solo debe contener números',
        ]));

        $metadata->addPropertyConstraint('nombre', new Assert\NotBlank(['message' => 'Este campo no puede estar vacío']));

        $metadata->addPropertyConstraint('salon', new Assert\NotBlank(['message' => 'Este campo no puede estar vacío']));

        $metadata->addPropertyConstraint('acudiente', new Assert\NotBlank(['message' => 'Este campo no puede estar vacío']));
        $metadata->addPropertyConstraint('acudiente', new Assert\Regex([
            'pattern' => '/^[\p{L} ]+$/u',
            'message' => 'El nombre del acudiente solo debe contener letras y espacios',
        ]));

        $metadata->addPropertyConstraint('edad', new Assert\NotBlank(['message' => 'Este campo no puede estar vacío']));
        $metadata->addPropertyConstraint('edad', new Assert\Positive(['message' => 'La edad debe ser un número positivo']));

        $metadata->addPropertyConstraint('genero', new Assert\NotBlank(['message' => 'Este campo no puede estar vacío']));
        $metadata->addPropertyConstraint('genero', new Assert\Regex([
            'pattern' => '/^[\p{L} ]+$/u',
            'message' => 'El género solo debe contener letras',
        ]));
    }
}
