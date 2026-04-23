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

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function execute(): string
    {
        try {
            $dbConnection = new db($this->config);

            $data = adminHandler::takeDataFromPost();

            if (empty($_SESSION['auth']) && isset($data['login'], $data['pass'])) {
                $auth = new authManager($dbConnection, $data);
                $auth->auth();
            }

            if (!rolechecker::checkIsadmin()) {
                throw new RoleErr("Доступ запрещен");
            }

            $dto = adminMapper::fromArray($data);
            $manage = new adminManager($dbConnection);
            
            $response = ['status' => 'success'];

            switch ($data['action'] ?? 'list') {
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

            return json_encode($response);

        } catch (inputErr $e) {
            return $this->renderError($e, 400, 'data get err');

        } catch (RoleErr $e) {
            return $this->renderError($e, 403, 'forbidden');

        } catch (dbActionErr $e) {
            return $this->renderError($e, 500, 'database action error');

        } catch (dbAdminErr $e) {
            return $this->renderError($e, 500, 'database connection error');

        } catch (Exception $e) {
            return $this->renderError($e, 500, 'unexpected error');
        }
    }


    private function renderError(Exception $e, int $httpCode, string $publicMessage): string
    {
        http_response_code($httpCode);
        error_log($publicMessage . ': ' . $e->getMessage());
        return json_encode([
            'status' => 'error', 
            'message' => $publicMessage
        ]);
    }
}
