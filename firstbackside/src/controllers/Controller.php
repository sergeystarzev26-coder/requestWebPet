<?php

namespace App\Controllers;

require_once __DIR__ . '/config.php';

use App\db\db;
use App\Services\requestHandler;
use App\Mappers\Mapper;
use App\Services\validator;
use App\Services\requestAdder;
use App\Exceptions\ValidationException;
use App\Exceptions\DatabaseException;
use Exception;

//класс контроллера как входная точка бизнес логики
class Controller
// как параметры контроллер принимает только конфиг.все остальное располагается локально.
{
    protected array $config;

    protected db $db;

    protected requestAdder $requestAdder;

    public function __construct(array $config, db $db, requestAdder $requestAdder)
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
            $data = requestHandler::takeDataFromPost();
            validator::validateData($data);
            $dto = Mapper::fromArray($data);

            $dbConnection = $this->db;
            $requestAdder = $this->requestAdder;
            $requestAdder->addDataToDb($dto);

            return json_encode(['status' => 'success']);
        } catch (ValidationException $e) {
            return $this->renderError($e, 403, 'incorrect data');
        } catch (DatabaseException $e) {
            return $this->renderError($e, 500, 'database connection error');
        } catch (Exception $e) {
            return $this->renderError($e, 500, 'unexpected error');
        }
    }

    //метод отчета ошибок.при возникновее в блоках catch вызывается метод и передаются параметры в зависимости от ошибок
    private function renderError(Exception $e, int $httpCode, string $publicMessage): string
    {
        http_response_code($httpCode);

        $errorData = [
            'time'    => date('Y-m-d H:i:s'),
            'level'   => 'critical',
            'message' => 'unexpected err',
            'details' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];

        error_log(json_encode($errorData, JSON_UNESCAPED_UNICODE));

        return json_encode([
            'status'  => 'error',
            'message' => $publicMessage
        ]);
    }
}
