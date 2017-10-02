<?

namespace UW\Modules\Notify\Entities;

class Statements implements IEntity
{
    use EntityHelper;

    const ENTITY_CODE = 'Statements';

    /**
     * @var string
     */
    protected $message;
}