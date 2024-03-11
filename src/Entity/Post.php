<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?PostStatus $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Favorite::class)]
    private Collection $favorites;

    #[ORM\ManyToMany(targetEntity: UserParticipation::class, mappedBy: 'post')]
    private Collection $userParticipations;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: AdminComment::class)]
    private Collection $adminComments;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    /**
     * @ORM\Column(type="string")
     */
    private $author;

    /**
     * @ORM\Column(type="string")
     */
    private $category;
    

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->userParticipations = new ArrayCollection();
        $this->adminComments = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?PostStatus
    {
        return $this->status;
    }

    public function setStatus(?PostStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setPost($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getPost() === $this) {
                $favorite->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserParticipation>
     */
    public function getUserParticipations(): Collection
    {
        return $this->userParticipations;
    }

    public function addUserParticipation(UserParticipation $userParticipation): static
    {
        if (!$this->userParticipations->contains($userParticipation)) {
            $this->userParticipations->add($userParticipation);
            $userParticipation->addPost($this);
        }

        return $this;
    }

    public function removeUserParticipation(UserParticipation $userParticipation): static
    {
        if ($this->userParticipations->removeElement($userParticipation)) {
            $userParticipation->removePost($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, AdminComment>
     */
    public function getAdminComments(): Collection
    {
        return $this->adminComments;
    }

    public function addAdminComment(AdminComment $adminComment): static
    {
        if (!$this->adminComments->contains($adminComment)) {
            $this->adminComments->add($adminComment);
            $adminComment->setPost($this);
        }

        return $this;
    }

    public function removeAdminComment(AdminComment $adminComment): static
    {
        if ($this->adminComments->removeElement($adminComment)) {
            // set the owning side to null (unless already changed)
            if ($adminComment->getPost() === $this) {
                $adminComment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getType(): ?Category
    {
        return $this->type;
    }

    public function setType(?Category $type): static
    {
        $this->type = $type;

        return $this;
    }
}