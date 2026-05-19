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
use App\Services\errorcathcer;
use Exception;
use PDOException;
use Throwable;

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
            $dbResult = $this->manager->Manage($dto);
            $response = [
                        'status' => 'success',
                        'data'=> $dbResult,
                        ];

            // Определяем действие
            return json_encode($response, JSON_UNESCAPED_UNICODE);
        } catch (inputErr $e) {
            return Errorcathcer::error($e, 400, 'data get err');
        } catch (RoleErr $e) {
            return Errorcathcer::error($e, 403, 'forbidden');
        } catch (dbActionErr $e) {
            return Errorcathcer::error($e, 500, 'database action error');
        } catch (dbAdminErr $e) {
            return Errorcathcer::error($e, 500, 'database error');
        } catch (PDOException $e) {
            return Errorcathcer::error($e, 500, 'database error');
        } catch (Throwable $e){
            return Errorcathcer::error($e, 500, 'unexpected error');
        }

    }
}
