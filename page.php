<?php

abstract class Page {
    const ACTION = "action";
    const ADD_NEW = "add_new";
    const EDIT = "edit";
    const EDIT_VIEW = "edit_view";
    const CREATE_VIEW = "create_view";
    const DELETE = "delete";
    
    private string $title;
    private string $tableName;

    public function getTitle()
    {
        return $this->title;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function __construct()
    {
        $this -> title = $this->passTitle();
        $this -> tableName = $this->passTableName();
    }

    protected abstract function passTitle():string;
    protected abstract function passTableName():string;
    protected abstract function enterModelDataFromForm(): void;
    protected abstract function generateViewAll(): string;
    protected abstract function generateViewEdit(): string;
    protected abstract function generateViewCreate(): string;
    protected abstract function addNew(): void;
    protected abstract function edit(): void;
    protected abstract function delete(): void;

    protected function generateHead():string{
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

    protected function generateFooter():string{
        return '
                <script src="js/bootstrap.min.js"></script>
            </body>
    
        </html>';
    }

    protected static function openConnection(): PDO{
        try{
            return new PDO("mysql:host=localhost;dbname=phpadvanced","root");
        }catch(PDOException $e){
            trigger_error("Fatal error", E_USER_ERROR);
        }
    }

    public function initialize(): void {
        print_r($_POST);
        echo $this->generateHead();
        switch($_POST[self::ACTION] ?? null) {
            case self::ADD_NEW:
                $this->addNew();
                echo $this->generateViewAll();
                echo "Dodanie do bazy danych";
                break;
            case self::EDIT:
                $this->edit();
                echo $this->generateViewAll();
                echo "Edycja w bazie danych";
                break;
            case self::EDIT_VIEW:
                echo $this->generateViewEdit();
                echo "Widok edycji";
                break;
            case self::CREATE_VIEW:
                echo $this->generateViewCreate();
                echo "Dodanie widoku";
                break;
            case self::DELETE:
                $this->delete();
                echo $this->generateViewAll();
                echo "Usuwanie z bazy danych";
                break;
            default:
                echo $this->generateViewAll();
                echo "Wyświetl wszystkie";
                break;
        }
        echo $this->generateFooter();
    }
}
