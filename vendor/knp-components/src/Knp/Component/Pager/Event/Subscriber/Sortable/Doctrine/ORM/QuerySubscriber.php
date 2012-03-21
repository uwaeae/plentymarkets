<?php

namespace Knp\Component\Pager\Event\Subscriber\Sortable\Doctrine\ORM;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Knp\Component\Pager\Event\ItemsEvent;
use Knp\Component\Pager\Event\Subscriber\Sortable\Doctrine\ORM\Query\OrderByWalker;
use Knp\Component\Pager\Event\Subscriber\Paginate\Doctrine\ORM\Query\Helper as QueryHelper;
use Doctrine\ORM\Query;

class QuerySubscriber implements EventSubscriberInterface
{
    public function items(ItemsEvent $event)
    {
        if ($event->target instanceof Query) {
            $alias = $event->options['alias'];
            if (isset($_GET[$alias.'sort'])) {
                $dir = strtolower($_GET[$alias.'direction']) === 'asc' ? 'asc' : 'desc';
                $parts = explode('.', $_GET[$alias.'sort']);
                if (count($parts) != 2) {
                    throw new \UnexpectedValueException('Invalid sort key came by request, should be example "entityAlias.field" like: "article.title"');
                }

                if (isset($event->options['whitelist'])) {
                    if (!in_array($_GET[$alias.'sort'], $event->options['whitelist'])) {
                        throw new \UnexpectedValueException("Cannot sort by: [{$_GET[$alias.'sort']}] this field is not in whitelist");
                    }
                }

                $event->target
                    ->setHint(OrderByWalker::HINT_PAGINATOR_SORT_ALIAS, current($parts))
                    ->setHint(OrderByWalker::HINT_PAGINATOR_SORT_DIRECTION, $dir)
                    ->setHint(OrderByWalker::HINT_PAGINATOR_SORT_FIELD, end($parts))
                ;
                QueryHelper::addCustomTreeWalker($event->target, 'Knp\Component\Pager\Event\Subscriber\Sortable\Doctrine\ORM\Query\OrderByWalker');
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'knp_pager.items' => array('items', 1)
        );
    }
}