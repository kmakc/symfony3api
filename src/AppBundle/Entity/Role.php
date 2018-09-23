<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Annotation as App;

/**
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person")
     * @App\DeserializeEntity(type="AppBundle\Entity\Person", idField="id", idGetter="getId", setter="setPerson")
     * @Assert\NotBlank()
     */
    private $person;

    /**
     * @var string
     *
     * @ORM\Column(name="played_name", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=100)
     */
    private $playedName;

    /**
     * @var Movie
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="roles")
     */
    private $movie;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return string
     */
    public function getPlayedName()
    {
        return $this->playedName;
    }

    /**
     * @param string $playedName
     */
    public function setPlayedName($playedName)
    {
        $this->playedName = $playedName;
    }

    /**
     * @return Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * @param Movie $movie
     */
    public function setMovie($movie)
    {
        $this->movie = $movie;
    }
}
