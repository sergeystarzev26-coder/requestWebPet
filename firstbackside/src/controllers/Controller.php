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

class Controller
{
    protected array $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function execute(): string
    {
        try {
            $data = requestHandler::takeDataFromPost();
            validator::validateData($data);
            $dto = Mapper::fromArray($data);

            $dbConnection = new db($this->config['db']);
            $requestAdder = new RequestAdder($dbConnection);
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
