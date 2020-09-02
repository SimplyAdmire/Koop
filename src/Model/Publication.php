<?php
declare(strict_types = 1);

namespace SimplyAdmire\Koop\Model;

final class Publication
{

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $publicationDate;

    public function __construct(string $identifier, string $type, string $title, string $url, ?\DateTime $publicationDate = null)
    {
        $this->identifier = $identifier;
        $this->type = $type;
        $this->title = $title;
        $this->url = $url;
        if ($publicationDate instanceof \DateTime) {
            $this->publicationDate = $publicationDate;
        }
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublicationDate(): ?\DateTime
    {
        return $this->publicationDate;
    }

    /**
     * @param \DateTime $publicationDate
     * @return \DateTime
     */
    public function setPublicationDate(\DateTime $publicationDate)
    {
        return $this->publicationDate = $publicationDate;
    }

}
