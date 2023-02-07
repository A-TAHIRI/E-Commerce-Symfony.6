<?php

namespace App\Entity;

use App\Entity\Traits\SlugTrait;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{

    use SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;


    #[ORM\Column(type:'integer')]
    private  $categorieOrder ;

    #[ORM\ManyToOne( targetEntity: self::class ,inversedBy: 'categories')]
    #[ORM\JoinColumn(onDelete:'CASCADE')]
    private $parent;


    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private  $categories;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Product::class)]
    private Collection $products;

   
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function setcategorieOrder(int $categorieOrder): self
    {
         $this->categorieOrder=$categorieOrder;
         return $this;
    }
    public function getcategorieOrder(): ?int
    {
        return $this->categorieOrder;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }
    public function setParent(?self $parent): self{
         $this->parent = $parent;
         return $this;
        
    }
    public function getParent(): ?self{
        return $this->parent;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[]=$category;
            $category->setParent($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        
        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategorie($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategorie() === $this) {
                $product->setCategorie(null);
            }
        }

        return $this;
    }
}