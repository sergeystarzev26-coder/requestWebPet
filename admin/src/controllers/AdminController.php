<?php

namespace App\Controllers;

use App\Services\adminHandler;
use App\Services\errorcathcer;
use App\usecases\usecase; 
use App\Exceptions\inputErr;
use App\Exceptions\RoleErr;
use App\Exceptions\dbActionErr;
use App\Exceptions\dbAdminErr;
use App\Exceptions\AuthErr; 
use PDOException;
use Throwable;

class AdminController
{
    protected usecase $usecase;

    public function __construct(usecase $usecase)
    {
        $this->usecase = $usecase;
    }

    public function execute(): string
    {
        try {
            $rawdata = adminHandler::takeDataFromPost();
            
            $result = $this->usecase->execute($rawdata);
            
            $response = [
                'status' => 'success',
                'data'   => $result,
            ];
            
            return json_encode($response, JSON_UNESCAPED_UNICODE); 
            
        } catch (inputErr $e) {
            return errorcathcer::error($e, 400, 'data get err');
        } catch (AuthErr $e) { // <-- Перехватываем ошибку неверного логина/пароля
            return errorcathcer::error($e, 401, 'unauthorized');
        } catch (RoleErr $e) {
            return errorcathcer::error($e, 403, 'forbidden');
        } catch (dbActionErr $e) {
            return errorcathcer::error($e, 500, 'database action error');
        } catch (dbAdminErr $e) {
            return errorcathcer::error($e, 500, 'database error');
        } catch (PDOException $e) {
            return errorcathcer::error($e, 500, 'database error');
        } catch (Throwable $e) {
            return errorcathcer::error($e, 500, 'unexpected error');
        }
    }
}
