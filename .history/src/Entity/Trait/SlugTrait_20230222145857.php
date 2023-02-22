<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait SlugTrait {

    #[ORM\Column(length: 255)]
    private $slug;

    
    public function getSlug(): ?\DateTimeImmutable
    {
        return $this->slug;
    }

    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }




}