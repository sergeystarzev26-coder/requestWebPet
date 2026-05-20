<?php

namespace App\Controllers;
use App\Services\requestHandler;
use App\Exceptions\ValidationException;
use App\Exceptions\DatabaseException;
use App\Services\errorcathcer;
use App\usecase\usecase;
use Exception;

//класс контроллера как входная точка бизнес логики
class Controller
{
    protected usecase $usecase;
    public function __construct(usecase $usecase)
    {
        $this->usecase = $usecase;
    }

    public function execute(): string
    {
        try {
            $rawdata = requestHandler::takeDataFromPost();
            $this->usecase->execute($rawdata);
            return json_encode(['status' => 'success']);
        } catch (ValidationException $e) {
            return errorcathcer::error($e, 403, 'incorrect data');
        } catch (DatabaseException $e) {
            return errorcathcer::error($e, 500, 'database connection error');
        } catch (Exception $e) {
            return errorcathcer::error($e, 500, 'unexpected error');
        }
    }
}
