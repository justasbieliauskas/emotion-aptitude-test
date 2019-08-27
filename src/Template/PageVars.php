<?php

namespace App\Template;

use PDO;
use App\Page\TotalPages;
use App\Page\BoundedPage;
use App\Page\QueryStringPage;

/**
 * Represents a trio of [$currentPages, $perPage, $totalPages].
 */
class PageVars
{
    private $db;

    private $get;

    private $pageKey;

    private $perPage;

    public function __construct(PDO $db, array $get, string $pageKey, int $perPage)
    {
        $this->db = $db;
        $this->get = $get;
        $this->pageKey = $pageKey;
        $this->perPage = $perPage;
    }

    public function toArray(
        string $currentKey,
        string $perPageKey,
        string $totalKey
    ): array {
        $total = (new TotalPages($this->db, $this->perPage))->toInt();
        $current = new BoundedPage(
            new QueryStringPage($this->get, $this->pageKey, 1),
            $total,
            1
        );

        return [
            $currentKey => $current->toInt(),
            $perPageKey => $this->perPage,
            $totalKey => $total,
        ];
    }
}
