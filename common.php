<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/23/2022
 * Time: 3:22 PM
 */


require_once __DIR__ . "\Base\RepositoryInterface.php";
require_once __DIR__ . "\Base\AbstractAction.php";
require_once __DIR__ . "\Base\Actions\CreateAction.php";
require_once __DIR__ . "\Base\AbstractRepository.php";
require_once __DIR__ . "\Base\AbstractService.php";
require_once __DIR__ . "\Base\EntityManager.php";


require_once __DIR__ . "\Database\loadDatabase.php";
require_once __DIR__ . "\App\Rules\loadRules.php";
require_once __DIR__ . "\App\Helpers\loadHelpers.php";
require_once __DIR__ . "\App\Models\loadModels.php";
require_once __DIR__ . "\App\Http\User\autoload.php";


require_once __DIR__ . "\App\Http\Book\autoload.php";
require_once __DIR__ . "\App\Http\Genre\autoload.php";

