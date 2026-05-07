<?php

namespace App\Controllers;

use App\Db\db;
use App\Services\adminHandler;
use App\Services\authManager;
use App\Services\adminManager;
use App\Services\rolechecker;
use App\Mappers\adminMapper;
use App\Exceptions\inputErr;
use App\Exceptions\RoleErr;
use App\Exceptions\dbActionErr;
use App\Exceptions\dbAdminErr;
use Exception;

class AdminController
{
    private array $config;
    private db $db;
    private adminManager $manager;

    // Передаем всё необходимое через конструктор
    public function __construct(array $config, db $db, adminManager $manager)
    {
        $this->config = $config;
        $this->db = $db;
        $this->manager = $manager;
    }

    public function execute(): string
    {
        try {
            // Используем подключение
            $dbConnection = $this->db;
            $data = adminHandler::takeDataFromPost();

            // Авторизация
            if (empty($_SESSION['auth']) && isset($data['login'], $data['pass'])) {
                $auth = new authManager($dbConnection, $data);
                $auth->auth();
            }

            // Проверка прав
            if (!rolechecker::checkIsadmin()) {
                throw new RoleErr("Доступ запрещен");
            }
        
            $dto = adminMapper::fromArray($data);
            
            // Используем уже имеющийся менеджер
            $manage = $this->manager;
            $response = ['status' => 'success'];

            // Определяем действие
            $action = $data['action'] ?? 'list';

            switch ($action) {
                case 'delete':
                    $manage->deleteRequest($dto);
                    $response['message'] = 'delete complete';
                    break;

                case 'pause':
                    $manage->pauseRequest($dto);
                    $response['message'] = 'pause complete';
                    break;

                case 'unpause':
                    $manage->unpauseRequest($dto);
                    $response['message'] = 'unpause complete';
                    break;

                case 'find':
                    $manage->findRequest($dto);
                    $response['message'] = 'find complete';
                    break;

                case 'list':
                    $response['data'] = $manage->getAllRequests();
                    $response['message'] = 'all requests were printed';
                    break;

                default:
                    $response['message'] = 'unknown action';
                    break;
            }

            return json_encode($response, JSON_UNESCAPED_UNICODE);

        } catch (inputErr $e) {
            return $this->renderError($e, 400, 'data get err');
        } catch (RoleErr $e) {
            return $this->renderError($e, 403, 'forbidden');
        } catch (dbActionErr $e) {
            return $this->renderError($e, 500, 'database action error');
        } catch (dbAdminErr $e) {
            return $this->renderError($e, 500, 'database error');
        } catch (Exception $e) {
            return $this->renderError($e, 500, 'unexpected error');
        }
    }

    private function renderError(Exception $e, int $httpCode, string $publicMessage): string
    {
        if (!headers_sent()) {
            http_response_code($httpCode);
        }
        
        $errorData = [
            'time'    => date('Y-m-d H:i:s'),
            'level'   => 'critical',
            'details' => $e->getMessage(),
        ];
        
        error_log($publicMessage . ': ' . json_encode($errorData));
        
        return json_encode([
            'status' => 'error', 
            'message' => $publicMessage
        ], JSON_UNESCAPED_UNICODE);
    }
}
