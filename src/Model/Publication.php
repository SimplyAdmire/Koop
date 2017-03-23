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

    public function __construct(string $identifier, string $type, string $title, string $url, \DateTime $publicationDate)
    {
        $this->identifier = $identifier;
        $this->type = $type;
        $this->title = $title;
        $this->url = $url;
        $this->publicationDate = $publicationDate;
    }

    public static function createFromXml(string $xml): Publication
    {
        $xmlObject = \simplexml_load_string($xml);
        foreach($xmlObject->getDocNamespaces() as $strPrefix => $strNamespace) {
            if(empty($strPrefix)) {
                $strPrefix = '_';
            }
            $xmlObject->registerXPathNamespace($strPrefix, $strNamespace);
        }

        return new static(
            (string)$xmlObject->xpath('//dcterms:identifier')[0],
            (string)$xmlObject->xpath('//dcterms:type')[0],
            (string)$xmlObject->xpath('//dcterms:title')[0],
            (string)$xmlObject->xpath('//_:url')[0],
            \DateTime::createFromFormat('Y-m-d', (string)$xmlObject->xpath('//dcterms:available')[0])
        );
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
     * @return \DateTime
     */
    public function getPublicationDate(): \DateTime
    {
        return $this->publicationDate;
    }

}
