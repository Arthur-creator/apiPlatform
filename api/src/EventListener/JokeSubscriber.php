<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class JokeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::VIEW => ['moyRate', EventPriorities::PRE_WRITE],
        ];
    }

    public function moyRate(EventSubscriberInterface $event)
    {
        dd('test');
        $joke = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$joke instanceof Joke || Request::METHOD_POST !== $method) {
            return;
        }
//        calculer la moyenne de tous les rates de la joke
        $moy = 0;
        $rates = $joke->getRates();
        foreach ($rates as $rate) {
            $moy += $rate->getRate();
        }
        $moy = $moy / count($rates);
        return $moy;

    }
}
