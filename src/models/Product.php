<?php

namespace src\models;

use Exception;


// all these fields inside the class should be private, but it's harder to convert to json then
class Product
{
    // the product object can be valid with a null id, cause that means it just wasn't stored
    public ?int $id;

    // name must be valid, not null, less than 255 length
    public string $name;

    // valid price, positive non 0 integer, represents "cents"
    public int $price;

    // less than 255 length or null, not mandatory
    public ?string $description;

    // I'll store it as base64, since that's how I'm getting it from the HTTP POST json body
    public ?string $image;

    // should be convertible to string, ideally YYYY-MM-DD, not mandatory
    public ?string $availabilityDate;

    // just a bool, not mandatory
    public ?bool $isInStock;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->description = $data['description'] ?? null;
        $this->image = $data['image'] ?? null;
        $this->availabilityDate = $data['availabilityDate'] ?? null;
        $this->isInStock = $data['isInStock'] ?? null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getAvailabilityDate(): ?string
    {
        return $this->availabilityDate;
    }

    public function setAvailabilityDate(?string $availabilityDate): void
    {
        $this->availabilityDate = $availabilityDate;
    }

    public function isInStock(): ?bool
    {
        return $this->isInStock;
    }

    public function setIsInStock(?bool $isInStock): void
    {
        $this->isInStock = $isInStock;
    }
}