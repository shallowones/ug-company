<?

namespace UW\Modules\Notify\Entities;


class Test implements IEntity
{
    use EntityHelper;

    const ENTITY_CODE = 'Test';

    /**
     * @var string
     */
    protected $name;
}