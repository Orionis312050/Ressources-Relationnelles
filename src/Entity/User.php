<?php

namespace App\Entity;



use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Security\Role\RoleConverter;
use App\Repository\RoleRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $online = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar_img = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToMany(targetEntity: Favorite::class, mappedBy: 'user')]
    private Collection $post;

    #[ORM\ManyToMany(targetEntity: UserParticipation::class, mappedBy: 'user')]
    private Collection $userParticipations;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: AdminComment::class)]
    private Collection $adminComments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'follower', targetEntity: Follow::class)]
    private Collection $follows;

    #[ORM\OneToMany(mappedBy: 'following', targetEntity: Follow::class)]
    private Collection $following;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\OneToMany(mappedBy: 'reposted_by', targetEntity: Post::class)]
    private Collection $reposted;


    public function __construct()
    {
        $this->post = new ArrayCollection();
        $this->userParticipations = new ArrayCollection();
        $this->adminComments = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->reposted = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(?bool $online): static
    {
        $this->online = $online;

        return $this;
    }

    public function getAvatarImg(): ?string
    {
        return $this->avatar_img;
    }

    public function setAvatarImg(?string $avatar_img): static
    {
        $this->avatar_img = $avatar_img;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, Favorite>
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite->add($favorite);
            $favorite->addUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorite->removeElement($favorite)) {
            $favorite->removeUser($this);
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
            $userParticipation->addUser($this);
        }

        return $this;
    }

    public function removeUserParticipation(UserParticipation $userParticipation): static
    {
        if ($this->userParticipations->removeElement($userParticipation)) {
            $userParticipation->removeUser($this);
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
            $adminComment->setAdmin($this);
        }

        return $this;
    }

    public function removeAdminComment(AdminComment $adminComment): static
    {
        if ($this->adminComments->removeElement($adminComment)) {
            // set the owning side to null (unless already changed)
            if ($adminComment->getAdmin() === $this) {
                $adminComment->setAdmin(null);
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Follow>
     */
    public function getFollows(): Collection
    {
        return $this->follows;
    }

    public function addFollow(Follow $follow): static
    {
        if (!$this->follows->contains($follow)) {
            $this->follows->add($follow);
            $follow->setFollower($this);
        }

        return $this;
    }

    public function removeFollow(Follow $follow): static
    {
        if ($this->follows->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getFollower() === $this) {
                $follow->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Follow>
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(Follow $following): static
    {
        if (!$this->following->contains($following)) {
            $this->following->add($following);
            $following->setFollowing($this);
        }

        return $this;
    }

    public function removeFollowing(Follow $following): static
    {
        if ($this->following->removeElement($following)) {
            // set the owning side to null (unless already changed)
            if ($following->getFollowing() === $this) {
                $following->setFollowing(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getRoles(): array
    {
        $roleConverter = new RoleConverter();
        return $roleConverter->intToRole($this->role->getId());
    }

    public function setRoles($role, RoleRepository $roleRepo): static
    {
        $roleConverter = new RoleConverter();
        $this->role = $roleConverter->roleToInt([$role], $roleRepo);
        return $this;
    }
}
