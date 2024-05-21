<?php

require_once("./Page.php");
require_once("./Task.php");

class TaskPage extends Page {

    private Task $model;

    public function getModel(): Task
    {
        return $this->model;
    }

    public function setModel(Task $model): self
    {
        $this->model = $model;
        return $this;
    }

    protected function passTitle(): string
    {
        return "Task";
    }

    protected function passTableName(): string
    {
        return "Tasks";
    }

    protected function generateHead(): string {
        return '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>
                    Tasks - Create
                </title>
                <link rel="stylesheet" href="css/bootstrap.min.css" />
                <link
                    href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
                    rel="stylesheet">
            </head>
            
            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <h1>Tasks - Create</h1>
                            <a href="?action=events" class="btn btn-secondary">View Events</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="POST">
                                <button class="btn btn-primary" name="'.self::ACTION.'" value="'.self::CREATE_VIEW.'"> Create new </button>
                                <button class="btn btn-primary">All</button>
                            </form>
                        </div>
                    </div>
                </div>
                </hr>';
    }
    

    protected function enterModelDataFromForm(): void
    {
        $model = new Task();
        $model->setTitle($_POST["Title"] ?? '');
        $model->setIsDone(isset($_POST["IsDone"]) && $_POST["IsDone"] === 'on');
        $model->setStartDateTime($_POST["StartDateTime"] ?? date("Y-m-d"));
        $model->setDescription($_POST["Description"] ?? '');
        $model->setDeadline($_POST["Deadline"] ?? date("Y-m-d"));
        $model->setInternalEventId(intval($_POST["InternalEventId"] ?? 0));
        $model->setNotes($_POST["Notes"] ?? '');
        $model->setCreationDateTime($_POST["CreationDateTime"] ?? date("Y-m-d"));
        $model->setEditDateTime($_POST["EditDateTime"] ?? date("Y-m-d"));
        $model->setId(intval($_POST["Id"] ?? 0));

        $this->setModel($model);
    }

    protected function fetchAll(): array
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE IsActive = 1";
        $stmt = self::openConnection()->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function generateViewAll(): string
    {
        $tasks = $this->fetchAll();
        $html = "<div class='container'><table class='table'>";
        $html .= "<thead><tr><th>Id</th><th>Title</th><th>Event</th><th>Is Done</th><th>Actions</th></tr></thead><tbody>";

        foreach ($tasks as $task) {
            $html .= "<tr>";
            $html .= "<td>{$task['Id']}</td>";
            $html .= "<td>{$task['Title']}</td>";
            $html .= "<td>{$this->fetchEventTitle($task['InternalEventId'])}</td>";
            $html .= "<td>" . ($task['IsDone'] ? "Yes" : "No") . "</td>";
            $html .= "<td>
                        <form method='post'>
                            <button class='btn btn-primary' name='" . self::ACTION . "' value='" . self::EDIT_VIEW . "'>Edit</button>
                            <button class='btn btn-danger' name='" . self::ACTION . "' value='" . self::DELETE . "'>Delete</button>
                            <input type='hidden' name='Id' value='{$task['Id']}'>
                        </form>
                      </td>";
            $html .= "</tr>";
        }

        $html .= "</tbody></table></div>";
        return $html;
    }

    protected function fetchEventTitle(int $eventId): string
    {
        $query = "SELECT Title FROM InternalEvents WHERE Id = :Id";
        $stmt = self::openConnection()->prepare($query);
        $stmt->bindValue(':Id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() ?: 'Unknown';
    }

    protected function generateViewEdit(): string {
        $id = $_POST['Id'] ?? 0;
        $task = $this->fetchById($id);
    
        return '
            <div class="container">
                <form method="post">
                    <input type="hidden" name="Id" value="' . $task['Id'] . '">
                    <!-- Add the rest of your form fields here, similar to create form, pre-filled with $task values -->
                    <button class="btn btn-primary" name="' . self::ACTION . '" value="' . self::EDIT . '">Update</button>
                </form>
            </div>';
    }
    
    protected function fetchById(int $id): array {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE Id = :Id";
        $stmt = self::openConnection()->prepare($query);
        $stmt->bindValue(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    protected function generateViewCreate(): string
    {
        return '
        <div class="container">
            <form method="post">
                <div class="row gy-3">
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="input-group">
                            <label class="input-group-text">
                                <i class="material-icons-round align-middle">label</i>
                                Title
                            </label>
                            <input class="form-control validate" name="Title">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="input-group">
                            <label class="input-group-text">
                                <i class="material-icons-round align-middle">event</i>
                                Event
                            </label>
                            <select class="form-control validate" name="InternalEventId">'.
                            $this->generateEventOptions().'
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="row">
                            <div class="col-auto">
                                <label class="form-check-label">
                                    Done
                                    <i class="material-icons-round align-middle">check</i>
                                </label>
                            </div>
                            <div class="form-switch form-check col-auto">
                                <input class="form-check-input validate" type="checkbox" name="IsDone">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="input-group">
                            <label class="input-group-text">
                                <i class="material-icons-round palette-accent-text-color align-middle">event</i>
                                Start date
                            </label>
                            <input class="form-control validate" type="date" name="StartDateTime">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="input-group">
                            <label class="input-group-text">
                                <i class="material-icons-round palette-accent-text-color align-middle">today</i>
                                Deadline
                            </label>
                            <input class="form-control validate" type="date" name="Deadline">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label">
                            <i class="material-icons-round palette-accent-text-color align-middle">description</i>
                            Description
                        </label>
                        <textarea class="form-control validate" name="Description"></textarea>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label">
                            <i class="material-icons-round palette-accent-text-color align-middle">notes</i>
                            Notes
                        </label>
                        <textarea class="form-control validate" name="Notes"></textarea>
                    </div>
                    <div class="col-sm-12">
                        <button class="btn btn-primary" name="'.self::ACTION.'" value="'.self::ADD_NEW.'">Create</button>
                    </div>
                </div>
            </form>
        </div>';
    }

    protected function generateEventOptions(): string
    {
        $query = "SELECT Id, Title FROM InternalEvents";
        $stmt = self::openConnection()->query($query);
        $options = "";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $options .= "<option value='{$row['Id']}'>{$row['Title']}</option>";
        }

        return $options;
    }

    protected function addNew(): void
    {
        $this->enterModelDataFromForm();
        $query = "INSERT INTO " . $this->getTableName() . " 
        (Title, IsDone, StartDateTime, Description, Deadline, InternalEventId, CreationDateTime, EditDateTime, Notes, IsActive)
        VALUES (:Title, :IsDone, :StartDateTime, :Description, :Deadline, :InternalEventId, CURDATE(), CURDATE(), :Notes, 1)";

        $stmt = self::openConnection()->prepare($query);
        $stmt->bindValue(":Title", $this->getModel()->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(":IsDone", $this->getModel()->getIsDone(), PDO::PARAM_BOOL);
        $stmt->bindValue(":StartDateTime", $this->getModel()->getStartDateTime(), PDO::PARAM_STR);
        $stmt->bindValue(":Description", $this->getModel()->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(":Deadline", $this->getModel()->getDeadline(), PDO::PARAM_STR);
        $stmt->bindValue(":InternalEventId", $this->getModel()->getInternalEventId(), PDO::PARAM_INT);
        $stmt->bindValue(":Notes", $this->getModel()->getNotes(), PDO::PARAM_STR);

        $stmt->execute();
    }

    protected function edit(): void {
        $this->enterModelDataFromForm();
        $query = "UPDATE " . $this->getTableName() . " 
                  SET Title = :Title, IsDone = :IsDone, StartDateTime = :StartDateTime, 
                      Description = :Description, Deadline = :Deadline, InternalEventId = :InternalEventId, 
                      EditDateTime = CURDATE(), Notes = :Notes 
                  WHERE Id = :Id";
    
        $stmt = self::openConnection()->prepare($query);
        $stmt->bindValue(":Title", $this->getModel()->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(":IsDone", $this->getModel()->getIsDone(), PDO::PARAM_BOOL);
        $stmt->bindValue(":StartDateTime", $this->getModel()->getStartDateTime(), PDO::PARAM_STR);
        $stmt->bindValue(":Description", $this->getModel()->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(":Deadline", $this->getModel()->getDeadline(), PDO::PARAM_STR);
        $stmt->bindValue(":InternalEventId", $this->getModel()->getInternalEventId(), PDO::PARAM_INT);
        $stmt->bindValue(":Notes", $this->getModel()->getNotes(), PDO::PARAM_STR);
        $stmt->bindValue(":Id", $this->getModel()->getId(), PDO::PARAM_INT);
    
        $stmt->execute();
    }
    

    protected function delete(): void {
        $id = intval($_POST['Id'] ?? 0);
        if ($id > 0) {
            $query = "UPDATE " . $this->getTableName() . " SET IsActive = 0 WHERE Id = :Id";
            $stmt = self::openConnection()->prepare($query);
            $stmt->bindValue(":Id", $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    
}
?>
