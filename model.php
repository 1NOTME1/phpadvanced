<?php
class Model
{
    private int $Id;
    private string $Title;
    private string $CreationDateTime;
    private string $EditDateTime;
    private string $Notes;
    public function getId(): int
    {
        return $this->Id;
    }
    public function setId(int $Id): void
    {
        $this->Id = $Id;
    }
    public function getTitle(): string
    {
        return $this->Title;
    }
    public function setTitle(string $Title): void
    {
        $this->Title = $Title;
    }
    public function getCreationDateTime(): string
    {
        return $this->CreationDateTime;
    }
    public function setCreationDateTime(string $CreationDateTime): void
    {
        $this->CreationDateTime = $CreationDateTime;
    }
    public function getEditDateTime(): string
    {
        return $this->EditDateTime;
    }
    public function setEditDateTime(string $EditDateTime): void
    {
        $this->EditDateTime = $EditDateTime;
    }
    public function getNotes(): string
    {
        return $this->Notes;
    }
    public function setNotes(string $Notes): void
    {
        $this->Notes = $Notes;
    }
}