<?php

namespace App\Controllers;
use App\db\db;
use App\Db\DbInterface;
use App\Services\requestHandler;
use App\Mappers\Mapper;
use App\Services\validator;
use App\Services\requestAdder;
use App\Exceptions\ValidationException;
use App\Exceptions\DatabaseException;
use App\Services\errorcathcer;
use Exception;

//класс контроллера как входная точка бизнес логики
class Controller
{
    protected array $config;

    protected DbInterface $db;

    protected requestAdder $requestAdder;

    public function __construct(array $config, DbInterface $db, requestAdder $requestAdder)
    {
        $this->config = $config;
        $this->db = $db;
        $this->requestAdder = $requestAdder;
    }
    //метод execute выполняет все необходимые методы в порядке:
    // получение данных->валидация->создание подключения к бд->выполнение операции->отчет в формате json
    public function execute(): string
    {
        try {
            header('Content-Type: application/json; charset=utf-8');
            $data = requestHandler::takeDataFromPost();
            validator::validateData($data);
            $dto = Mapper::fromArray($data);

            $this->requestAdder->addDataToDb($dto);

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
