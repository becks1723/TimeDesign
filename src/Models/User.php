<?php

namespace TimeDesign\Models;

class User {
    private int $id;
    private string $rcs;
    private \DateTime $created_on;
    private bool $is_admin;

    public function __construct(int $id, string $rcs, string $created_on, bool $is_admin) {
        $this->id = $id;
        $this->rcs = $rcs;
        $this->created_on = new \DateTime($created_on);
        $this->is_admin = $is_admin;
    }

    public function getRcs(): string {
        return $this->rcs;
    }

    public function getCreatedOn(): \DateTime {
        return $this->created_on;
    }

    public function isIsAdmin(): bool {
        return $this->is_admin;
    }

    public function getId(): int {
        return $this->id;
    }
}
