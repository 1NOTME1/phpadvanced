<?php
require_once("./model.php");

class InternalEvent extends Model
{
    private string $Link;
    private bool $IsPublic;
    private bool $IsCancelled;
    private string $PublishDateTime;
    private string $EventDateTime;
    private string $ShortDescription;
    private string $ContentHTML;
    private string $MetaDescription;
    private string $MetaTags;

    public function getLink(): string
    {
        return $this->Link;
    }

    public function setLink(string $Link): void
    {
        $this->Link = $Link;
    }

    public function isIsPublic(): bool
    {
        return $this->IsPublic;
    }

    public function setIsPublic(bool $IsPublic): void
    {
        $this->IsPublic = $IsPublic;
    }

    public function isIsCancelled(): bool
    {
        return $this->IsCancelled;
    }

    public function setIsCancelled(bool $IsCancelled): void
    {
        $this->IsCancelled = $IsCancelled;
    }

    public function getPublishDateTime(): string
    {
        return $this->PublishDateTime;
    }

    public function setPublishDateTime(string $PublishDateTime): void
    {
        $this->PublishDateTime = $PublishDateTime;
    }

    public function getEventDateTime(): string
    {
        return $this->EventDateTime;
    }

    public function setEventDateTime(string $EventDateTime): void
    {
        $this->EventDateTime = $EventDateTime;
    }

    public function getShortDescription(): string
    {
        return $this->ShortDescription;
    }

    public function setShortDescription(string $ShortDescription): void
    {
        $this->ShortDescription = $ShortDescription;
    }

    public function getContentHTML(): string
    {
        return $this->ContentHTML;
    }

    public function setContentHTML(string $ContentHTML): void
    {
        $this->ContentHTML = $ContentHTML;
    }

    public function getMetaDescription(): string
    {
        return $this->MetaDescription;
    }

    public function setMetaDescription(string $MetaDescription): void
    {
        $this->MetaDescription = $MetaDescription;
    }

    public function getMetaTags(): string
    {
        return $this->MetaTags;
    }

    public function setMetaTags(string $MetaTags): void
    {
        $this->MetaTags = $MetaTags;
    }
}
?>
