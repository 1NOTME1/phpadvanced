<?php

require_once("./Page.php");
require_once("./internalEvent.php");

class InternalEventPage extends Page {

    private InternalEvent $model;

    /**
     * Get the value of model
     */
    public function getModel()
    {
        return $this->model;
    }
    /**
     * Set the value of model
     *
     * @return  self
     */
    public function setModel($model)
    {
        $this->model = $model;
 
        return $this;
    }

    protected function passTitle():string{
        return "Internal Event";
    }

    protected function passTableName(): string{
        return "InternalEvents";
    }

    protected function generateHead(): string {
        return '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>
                    Internal Events - Create
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
                            <h1>Internal Events - Create</h1>
                            <a href="?action=tasks" class="btn btn-secondary">View Tasks</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="POST">
                                <button class="btn btn-primary" name="'.self::ACTION.'" value="'.self::CREATE_VIEW.'"> Create new </button>
                                <button class="btn btn-primary" name="'.self::ACTION.'" value="">All</button>
                            </form>
                        </div>
                    </div>
                </div>
                </hr>';
    }

    protected function enterModelDataFromForm(): void {
        $_POST["Title"];
        $model = new InternalEvent();
        $model->setTitle($_POST["Title"]);
        $model->setLink($_POST["Link"]);
        $model->setPublishDateTime($_POST["PublishDateTime"]);
        $model->setEventDateTime($_POST["EventDateTime"]);
        $model->setIsPublic($_POST["IsPublic"] ?? false); 
        $model->setIsCancelled($_POST["IsCancelled"] ?? false);
        $model->setShortDescription($_POST["ShortDescription"]);
        $model->setContentHTML($_POST["ContentHTML"]);
        $model->setMetaDescription($_POST["MetaDescription"]);
        $model->setMetaTags($_POST["MetaTags"]);
        $model->setNotes($_POST["Notes"]);
        $model->setCreationDateTime($_POST["CreationDateTime"] ?? date("Y-m-d"));
        $model->setEditDateTime($_POST["EditDateTime"] ?? date("Y-m-d"));
        $model->setId($_POST["Id"] ?? 0);

        $this->setModel($model);
    }

    protected function fetchAll(): array {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE IsActive = 1";
        $stmt = self::openConnection()->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function generateViewAll(): string {
        $events = $this->fetchAll();
        $html = "<div class='container'><table class='table'>";
        $html .= "<thead><tr><th>Id</th><th>Title</th><th>Link</th><th>Is Public</th><th>Is Cancelled</th><th>Actions</th></tr></thead><tbody>";

        foreach ($events as $event) {
            $html .= "<tr>";
            $html .= "<td>{$event['Id']}</td>";
            $html .= "<td>{$event['Title']}</td>";
            $html .= "<td>{$event['Link']}</td>";
            $html .= "<td>" . ($event['IsPublic'] ? "Yes" : "No") . "</td>";
            $html .= "<td>" . ($event['IsCancelled'] ? "Yes" : "No") . "</td>";
            $html .= "<td>
                        <form method='post'>
                            <button class='btn btn-primary' name='" . self::ACTION . "' value='" . self::EDIT_VIEW . "'>Edit</button>
                            <button class='btn btn-danger' name='" . self::ACTION . "' value='" . self::DELETE . "'>Delete</button>
                            <input type='hidden' name='Id' value='{$event['Id']}'>
                        </form>
                      </td>";
            $html .= "</tr>";
        }

        $html .= "</tbody></table></div>";
        return $html;
    }

    protected function generateViewEdit(): string {
        $id = $_POST['Id'] ?? 0;
        $event = $this->fetchById($id);
    
        return '
            <div class="container">
                <form method="post">
                    <input type="hidden" name="Id" value="' . $event['Id'] . '">
                    <div class="row gy-3">
                        <div class="col-md-12 col-lg-6 col-xxl-4">
                            <div class="input-group">
                                <label class="input-group-text">
                                    <i class="material-icons-round align-middle">label</i>
                                    Title
                                </label>
                                <input class="form-control validate" name="Title" value="' . $event['Title'] . '">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xxl-4">
                            <div class="input-group">
                                <label class="input-group-text">
                                    <i class="material-icons-round align-middle">link</i>
                                    Link
                                </label>
                                <input class="form-control validate" name="Link" value="' . $event['Link'] . '">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xxl-4">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="form-check-label">
                                        Public
                                        <i class="material-icons-round align-middle">public</i>
                                    </label>
                                </div>
                                <div class="form-switch form-check col-auto">
                                    <input class="form-check-input validate" type="checkbox" name="IsPublic"' . ($event['IsPublic'] ? ' checked' : '') . '>
                                    <label class="form-check-label">
                                        <i class="material-icons-round align-middle">block</i>
                                        Private
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xxl-4">
                            <div class="row">
                                <div class="col-auto">
                                    <label class="form-check-label">
                                        Cancelled
                                        <i class="material-icons-round align-middle">cancel</i>
                                    </label>
                                </div>
                                <div class="form-switch form-check col-auto">
                                    <input class="form-check-input validate" type="checkbox" name="IsCancelled"' . ($event['IsCancelled'] ? ' checked' : '') . '>
                                    <label class="form-check-label">
                                        <i class="material-icons-round align-middle">public</i>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xxl-4">
                            <div class="input-group">
                                <label class="input-group-text">
                                    <i class="material-icons-round palette-accent-text-color align-middle">event</i>
                                    Event date
                                </label>
                                <input class="form-control validate" type="date" name="EventDateTime" value="' . $event['EventDateTime'] . '">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xxl-4">
                            <div class="input-group">
                                <label class="input-group-text">
                                    <i class="material-icons-round palette-accent-text-color align-middle">today</i>
                                    Publish date
                                </label>
                                <input class="form-control validate" type="date" name="PublishDateTime" value="' . $event['PublishDateTime'] . '">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label">
                                <i class="material-icons-round palette-accent-text-color align-middle">description</i>
                                Short description
                            </label>
                            <textarea class="form-control validate" name="ShortDescription">' . $event['ShortDescription'] . '</textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label">
                                <i class="material-icons-round palette-accent-text-color align-middle">newspaper</i>
                                Content
                            </label>
                            <textarea class="form-control validate" name="ContentHTML">' . $event['ContentHTML'] . '</textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label">
                                <i class="material-icons-round palette-accent-text-color align-middle">feed</i>
                                Meta description
                            </label>
                            <textarea class="form-control validate" name="MetaDescription">' . $event['MetaDescription'] . '</textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label">
                                <i class="material-icons-round palette-accent-text-color align-middle">subtitles</i>
                                Meta tags
                            </label>
                            <textarea class="form-control validate" name="MetaTags">' . $event['MetaTags'] . '</textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label">
                                <i class="material-icons-round palette-accent-text-color align-middle">notes</i>
                                Notes
                            </label>
                            <textarea class="form-control validate" name="Notes">' . $event['Notes'] . '</textarea>
                        </div>
                        <div class="col-sm-12">
                            <button class="btn btn-primary" name="' . self::ACTION . '" value="' . self::EDIT . '">Update</button>
                        </div>
                    </div>
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

    protected function generateViewCreate(): string {
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
                                <i class="material-icons-round align-middle">link</i>
                                Link
                            </label>
                            <input class="form-control validate" name="Link">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="row">
                            <div class="col-auto">
                                <label class="form-check-label">
                                    Public
                                    <i class="material-icons-round align-middle">public</i>
                                </label>
                            </div>
                            <div class="form-switch form-check col-auto">
                                <input class="form-check-input validate" type="checkbox" name="IsPublic">
                                <label class="form-check-label">
                                    <i class="material-icons-round align-middle">block</i>
                                    Private
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="row">
                            <div class="col-auto">
                                <label class="form-check-label">
                                    Cancelled
                                    <i class="material-icons-round align-middle">cancel</i>
                                </label>
                            </div>
                            <div class="form-switch form-check col-auto">
                                <input class="form-check-input validate" type="checkbox" name="IsCancelled">
                                <label class="form-check-label">
                                    <i class="material-icons-round align-middle">public</i>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="input-group">
                            <label class="input-group-text">
                                <i class="material-icons-round palette-accent-text-color align-middle">event</i>
                                Event date
                            </label>
                            <input class="form-control validate" type="date" name="EventDateTime">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-4">
                        <div class="input-group">
                            <label class="input-group-text">
                                <i class="material-icons-round palette-accent-text-color align-middle">today</i>
                                Publish date
                            </label>
                            <input class="form-control validate" type="date" name="PublishDateTime">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label">
                            <i class="material-icons-round palette-accent-text-color align-middle">description</i>
                            Short description
                        </label>
                        <textarea class="form-control validate" name="ShortDescription"></textarea>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label">
                            <i class="material-icons-round palette-accent-text-color align-middle">newspaper</i>
                            Content
                        </label>
                        <textarea class="form-control validate" name="ContentHTML"></textarea>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label">
                            <i class="material-icons-round palette-accent-text-color align-middle">feed</i>
                            Meta description
                        </label>
                        <textarea class="form-control validate" name="MetaDescription"></textarea>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label">
                            <i class="material-icons-round palette-accent-text-color align-middle">subtitles</i>
                            Meta tags
                        </label>
                        <textarea class="form-control validate" name="MetaTags"></textarea>
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

    protected function addNew(): void {
        $this-> enterModelDataFromForm();
        $query = "INSERT INTO " . $this->getTableName() . " 
        (Title, 
        Link, 
        IsPublic, 
        IsCancelled, 
        EventDateTime, 
        PublishDateTime, 
        ShortDescription, 
        ContentHTML, 
        MetaDescription, 
        MetaTags, 
        Notes, 
        CreationDateTime, 
        EditDateTime, 
        IsActive)
        VALUES (
        :Title, 
        :Link, 
        :IsPublic, 
        :IsCancelled, 
        :EventDateTime, 
        :PublishDateTime, 
        :ShortDescription, 
        :ContentHTML, 
        :MetaDescription, 
        :MetaTags, 
        :Notes, 
        CURDATE(),
        CURDATE(),
        1)";

        $query = self::openConnection()->prepare($query); 
        $query->bindValue(":Title", $this->getModel()->getTitle(), PDO::PARAM_STR);
        $query->bindValue(":Link", $this->getModel()->getLink(), PDO::PARAM_STR);
        $query->bindValue(":IsPublic", $this->getModel()->isIsPublic(), PDO::PARAM_BOOL);
        $query->bindValue(":IsCancelled", $this->getModel()->isIsCancelled(), PDO::PARAM_BOOL);
        $query->bindValue(":EventDateTime", $this->getModel()->getEventDateTime(), PDO::PARAM_STR);
        $query->bindValue(":PublishDateTime", $this->getModel()->getPublishDateTime(), PDO::PARAM_STR);
        $query->bindValue(":ShortDescription", $this->getModel()->getShortDescription(), PDO::PARAM_STR);
        $query->bindValue(":ContentHTML", $this->getModel()->getContentHTML(), PDO::PARAM_STR);
        $query->bindValue(":MetaDescription", $this->getModel()->getMetaDescription(), PDO::PARAM_STR);
        $query->bindValue(":MetaTags", $this->getModel()->getMetaTags(), PDO::PARAM_STR);
        $query->bindValue(":Notes", $this->getModel()->getNotes(), PDO::PARAM_STR);

        $query->execute();
    }

    protected function edit(): void {
        $this->enterModelDataFromForm();
        $query = "UPDATE " . $this->getTableName() . " 
                  SET Title = :Title, Link = :Link, IsPublic = :IsPublic, 
                      IsCancelled = :IsCancelled, EventDateTime = :EventDateTime, 
                      PublishDateTime = :PublishDateTime, ShortDescription = :ShortDescription, 
                      ContentHTML = :ContentHTML, MetaDescription = :MetaDescription, 
                      MetaTags = :MetaTags, Notes = :Notes, EditDateTime = CURDATE()
                  WHERE Id = :Id";
    
        $stmt = self::openConnection()->prepare($query);
        $stmt->bindValue(":Title", $this->getModel()->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(":Link", $this->getModel()->getLink(), PDO::PARAM_STR);
        $stmt->bindValue(":IsPublic", $this->getModel()->isIsPublic(), PDO::PARAM_BOOL);
        $stmt->bindValue(":IsCancelled", $this->getModel()->isIsCancelled(), PDO::PARAM_BOOL);
        $stmt->bindValue(":EventDateTime", $this->getModel()->getEventDateTime(), PDO::PARAM_STR);
        $stmt->bindValue(":PublishDateTime", $this->getModel()->getPublishDateTime(), PDO::PARAM_STR);
        $stmt->bindValue(":ShortDescription", $this->getModel()->getShortDescription(), PDO::PARAM_STR);
        $stmt->bindValue(":ContentHTML", $this->getModel()->getContentHTML(), PDO::PARAM_STR);
        $stmt->bindValue(":MetaDescription", $this->getModel()->getMetaDescription(), PDO::PARAM_STR);
        $stmt->bindValue(":MetaTags", $this->getModel()->getMetaTags(), PDO::PARAM_STR);
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
