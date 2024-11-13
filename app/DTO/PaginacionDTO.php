<?php

namespace App\DTO;

class PaginacionDTO{
    public $content;
    public int $size;
    public int $page;
    public int $totalPages;
    public int $totalElements;

    public function __construct($content, int $size, int $page, int $totalPages, int $totalElements) {
        $this->content = $content;
        $this->size = $size;
        $this->page = $page;
        $this->totalPages = $totalPages;
        $this->totalElements = $totalElements;
    }
}