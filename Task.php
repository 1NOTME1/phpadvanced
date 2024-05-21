<?php
require_once("./model.php");

class Task extends Model
{
    private string $Title;
    private bool $IsDone;
    private string $StartDateTime;
    private string $Description;
    private string $Deadline;
    private int $InternalEventId;

    public function getTitle(): string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): void
    {
        $this->Title = $Title;
    }

    public function getIsDone(): bool
    {
        return $this->IsDone;
    }

    public function setIsDone(bool $IsDone): void
    {
        $this->IsDone = $IsDone;
    }

    public function getStartDateTime(): string
    {
        return $this->StartDateTime;
    }

    public function setStartDateTime(string $StartDateTime): void
    {
        $this->StartDateTime = $StartDateTime;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): void
    {
        $this->Description = $Description;
    }

    public function getDeadline(): string
    {
        return $this->Deadline;
    }

    public function setDeadline(string $Deadline): void
    {
        $this->Deadline = $Deadline;
    }

    public function getInternalEventId(): int
    {
        return $this->InternalEventId;
    }

    public function setInternalEventId(int $InternalEventId): void
    {
        $this->InternalEventId = $InternalEventId;
    }
}
?>
