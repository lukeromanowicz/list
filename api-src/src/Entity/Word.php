<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="words",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="unique_user_word",
 *            columns={"user_id", "word"})
 *    }
 * )
 * @ORM\Entity(repositoryClass=WordRepository::class)
 */
class Word
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(
     *      name="user_id",
     *      referencedColumnName="id",
     *      onDelete="CASCADE",
     *      nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $word;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    //TODO: remove
//    public function toArray(): array {
//        return [
//            'id' =>  $this->getId(),
//            'user_id' => $this->getUserId(),
//            'word' => $this->getWord(),
//            'count' => $this->getCount()
//        ];
//    }
}
