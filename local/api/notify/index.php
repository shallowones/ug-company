<?
use Slim\Http\Request;
use Slim\Http\Response;


define('BITRIX_STATIC_PAGES', false);
define("NEED_AUTH", false);
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define("PERFMON_STOP", true);


require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");


$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];


$c = new \Slim\Container($configuration);
$app = new Slim\App();

$app->getContainer()['api'] = function () {
    /**
     * Регистрируем провайдер
     */

    return new \UW\Modules\Notify\Config\ApiController();
};

$app->add(function (Request $request, Response $response, $next) {

    try{
        $response = $next($request, $response);
    } catch (\UW\Modules\Notify\Exceptions\NotifyException $e) {
        return $response->withJson([
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ], 500);
    } catch (\Exception $e) {
        return $response->withJson([
            'code' => 0,
            'message' => $e->getMessage()
        ], 500);
    }

    return $response;
});

//Список сущностей
$app->get('/entities', function (Request $request, Response $response, $arguments) {
    /**
     * @var \UW\Modules\Notify\Config\ApiController $api
     */
    $api = $this->api;
    $data = $api->getEntitiesList();

    return $response->withJson($data);
});

//список событий
$app->group('/entities/{entityCode:[A-Za-z]+}/events', function () {

    $this->get('', function (Request $request, Response $response, $arguments){
        /**
         * @var \UW\Modules\Notify\Config\ApiController $api
         */
        $api = $this->api;
        $data = $api->getEventsByEntity($arguments['entityCode']);

        return $response->withJson($data);
    });

    $this->post('', function (Request $request, Response $response, $arguments) {
        /**
         * @var \UW\Modules\Notify\Config\ApiController $api
         */
        $api = $this->api;
        $body = $request->getParsedBody();
        $data = $api->registerEvent($body['eventCode'], $arguments['entityCode']);

        return $response->withJson($data);
    });

    $this->group('/{eventId:[0-9]+}', function () {

        $this->delete('', function (Request $request, Response $response, $arguments) {
            /**
             * @var \UW\Modules\Notify\Config\ApiController $api
             */
            $api = $this->api;
            $api->removeEvent($arguments['eventId']);

            return $response->withJson([]);
        });

        $this->group('/providers', function () {

            $this->get('', function (Request $request, Response $response, $arguments) {
                /**
                 * @var \UW\Modules\Notify\Config\ApiController $api
                 */
                $api = $this->api;
                $data = $api->getProviders($arguments['eventId']);

                return $response->withJson($data);

            });

            $this->post('', function (Request $request, Response $response, $arguments) {
                /**
                 * @var \UW\Modules\Notify\Config\ApiController $api
                 */
                $api = $this->api;
                $data = $api->registerProvider($arguments['eventId'], $request->getParsedBody()['providerCode']);

                return $response->withJson($data);
            });

            $this->group('/{providerId:[0-9]+}', function () {

                $this->get('', function (Request $request, Response $response, $arguments) {
                    /**
                     * @var \UW\Modules\Notify\Config\ApiController $api
                     */
                    $api = $this->api;
                    $data = $api->getProviderInfo($arguments['providerId']);

                    return $response->withJson($data);
                });

                $this->post('', function (Request $request, Response $response, $arguments) {
                    /**
                     * @var \UW\Modules\Notify\Config\ApiController $api
                     */
                    $api = $this->api;
                    $api->saveProviderInfo($arguments['providerId'], $request->getParsedBody());

                    return $response->withJson([]);
                });

                $this->delete('', function (Request $request, Response $response, $arguments) {
                    /**
                     * @var \UW\Modules\Notify\Config\ApiController $api
                     */
                    $api = $this->api;
                    $api->removeProvider($arguments['providerId']);

                    return $response->withJson([]);
                });

                $this->delete('/rule/{ruleId:[0-9]+}', function (Request $request, Response $response, $arguments) {
                    /**
                     * @var \UW\Modules\Notify\Config\ApiController $api
                     */
                    $api = $this->api;
                    $api->removeRule($arguments['ruleId']);

                    return $response->withJson([]);
                });

            });

        });
    });
});

$app->group('/dictionaries', function () {

    $this->get('/variables/template/{entityCode:[A-Za-z]+}', function (Request $request, Response $response, $arguments) {
        /**
         * @var \UW\Modules\Notify\Config\ApiController $api
         */
        $api = $this->api;

        return $response->withJson($api->getTemplateAlgorithmVariables($arguments['entityCode']));
    });

    $this->get('/variables/consumers', function (Request $request, Response $response, $arguments) {
        /**
         * @var \UW\Modules\Notify\Config\ApiController $api
         */
        $api = $this->api;

        return $response->withJson($api->getConsumersAlgorithmVariables());
    });

    $this->get('/logic', function (Request $request, Response $response, $arguments) {
        /**
         * @var \UW\Modules\Notify\Config\ApiController $api
         */
        $api = $this->api;

        return $response->withJson($api->getAssertionsLogicList());
    });

    $this->get('/assertions/{entityCode:[A-Za-z]+}', function (Request $request, Response $response, $arguments) {
        /**
         * @var \UW\Modules\Notify\Config\ApiController $api
         */
        $api = $this->api;

        return $response->withJson($api->getAssertionsList($arguments['entityCode']));
    });

});


$app->run();


