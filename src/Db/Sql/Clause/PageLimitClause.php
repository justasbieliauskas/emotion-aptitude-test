<?php

namespace App\Db\Sql\Clause;

use App\Page\PageInterface;
use App\Page\PerPageInterface;

class PageLimitClause implements ClauseInterface
{
    private $clause;

    private $currentPage;

    private $perPage;

    public function __construct(
        ClauseInterface $clause,
        PageInterface $currentPage,
        PerPageInterface $perPage
    ) {
        $this->clause = $clause;
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
    }

    public function asString(): string
    {
        $clause = $this->clause->asString();
        $limit = $this->perPage->toInt();
        $offset = ($this->currentPage->toInt() - 1) * $limit;

        return "$clause LIMIT $limit OFFSET $offset";
    }
}
